<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Services\ApprovalService;
use App\Services\PinVerificationService;
use App\Services\ApprovalCoverService;
use App\Http\Requests\Surat\StoreSuratRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    public function __construct(
        private ApprovalService $approval,
        private PinVerificationService $pinService,
        private ApprovalCoverService $coverService,
    ) {
        $this->middleware('auth');
    }

    // ── INDEX ───────────────────────────────────────────────
    public function index()
    {
        $user    = Auth::user()->load('profile');
        $jabatan = $user->profile?->jabatan;

    if ($jabatan) {
        $surats = Surat::with(['user', 'approvals'])
            ->whereHas('approvals', function ($q) use ($jabatan) {
                $q->where('jabatan', $jabatan)
                ->whereIn('status', ['waiting', 'approved', 'rejected', 'pending']) // sudah benar
                ->where('document_type', 'LIKE', 'surat_%');
            })
            ->latest()->paginate(15);
        } elseif ($user->hasRole('staff')) {
            // Staff — hanya surat milik sendiri
            $surats = Surat::with(['user', 'approvals'])
                ->where('user_id', $user->id)
                ->latest()->paginate(15);
        } else {
            // HR / Supervisor tanpa jabatan — lihat semua
            $surats = Surat::with(['user', 'approvals'])
                ->latest()->paginate(15);
        }

        return view('surat.index', compact('surats'));
    }

    // ── CREATE ──────────────────────────────────────────────
    public function create()
    {
        $this->authorize('create', Surat::class);
        return view('surat.create');
    }

    // ── STORE ───────────────────────────────────────────────
    public function store(StoreSuratRequest $request)
    {
        $this->authorize('store', Surat::class);

        $fileName = null;
        if ($request->hasFile('file_pdf')) {
            $fileName = $request->file('file_pdf')->store('surat', 'public');
        }

        $surat = Surat::create([
            'user_id'     => Auth::id(),
            'nomor_surat' => $this->generateNomorSurat(),
            'jenis_surat' => $request->validated()['jenis_surat'],
            'perihal'     => $request->validated()['perihal'],
            'file_pdf'    => $fileName,
            'status'      => 'submitted',
        ]);

        // Init approval 4 step
        $this->approval->initApproval('surat_' . $surat->jenis_surat, $surat->id);

        flash()->success('Surat berhasil dibuat dan dikirim untuk approval.');
        return redirect()->route('surat.show', $surat->id);
    }

    // ── SHOW ────────────────────────────────────────────────
    public function show(Surat $surat)
    {
        $this->authorize('view', $surat);

        $documentType = 'surat_' . $surat->jenis_surat;
        $steps      = $this->approval->getStatus($documentType, $surat->id);
        $authUser   = Auth::user()->load('profile');
        $canApprove = $this->approval->canApprove($documentType, $surat->id, $authUser);
        $waitingStep = $this->approval->getWaitingStep($documentType, $surat->id);

        return view('surat.show', compact('surat', 'steps', 'canApprove', 'waitingStep'));
    }

    // ── EDIT ────────────────────────────────────────────────
    public function edit(Surat $surat)
    {
        $this->authorize('edit', $surat);
        return view('surat.edit', compact('surat'));
    }

    // ── UPDATE (resubmit setelah revisi) ────────────────────
    public function update(StoreSuratRequest $request, Surat $surat)
    {
        $this->authorize('update', $surat);

        if ($request->hasFile('file_pdf')) {
            if ($surat->file_pdf && file_exists(storage_path('app/public/' . $surat->file_pdf))) {
                unlink(storage_path('app/public/' . $surat->file_pdf));
            }
            $surat->update(['file_pdf' => $request->file('file_pdf')->store('surat', 'public')]);
        }

        $surat->update([
            'status'        => 'submitted',
            'catatan_revisi' => null,
        ]);

        // Reset approval dari step 1
        $this->approval->resubmit('surat_' . $surat->jenis_surat, $surat->id);

        flash()->success('Surat berhasil direvisi dan dikirim ulang untuk approval.');
        return redirect()->route('surat.show', $surat->id);
    }

    // ── APPROVE ─────────────────────────────────────────────
    public function approve(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
            'pin'     => 'required|string',
        ], [
            'pin.required' => 'PIN wajib diisi untuk menyetujui surat.',
        ]);

        $user = Auth::user()->load('profile');

        // Verifikasi PIN
        if (!$this->pinService->verify($user, $request->pin)) {
            flash()->error('PIN salah. Silakan coba lagi.');
            return redirect()->back();
        }

        // Ambil path TTD untuk snapshot
        $ttdSnapshot = $this->pinService->getTtdPath($user);

        $result = $this->approval->approve(
            'surat_' . $surat->jenis_surat,
            $surat->id,
            $user,
            $request->catatan ?? '',
            $ttdSnapshot
        );

        \Log::info('Approve result', $result);
        \Log::info('Cover PDF path', ['cover_pdf_path' => $surat->cover_pdf_path]);

        if (!$result['success']) {
            flash()->error($result['message']);
            return redirect()->back();
        }

        // Jika semua step selesai, update status surat + generate PDF cover
        if ($result['selesai']) {
            $surat->update(['status' => 'approved_owner']);

            // Generate PDF cover approval
            try {
                    $coverPath = $this->coverService->generateCover($surat);
                    $surat->update(['cover_pdf_path' => $coverPath]);
                    \Log::info('Cover generated: ' . $coverPath); // tambah ini
                } catch (\Exception $e) {
                    \Log::error('Gagal generate cover PDF: ' . $e->getMessage());
                }
        }

        flash()->success($result['message']);
        return redirect()->route('surat.show', $surat->id);
    }

    // ── REJECT ──────────────────────────────────────────────
    public function reject(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:5|max:500',
        ], [
            'catatan_revisi.required' => 'Catatan revisi wajib diisi saat menolak.',
            'catatan_revisi.min'      => 'Catatan revisi minimal 5 karakter.',
        ]);

        $result = $this->approval->reject(
            'surat_' . $surat->jenis_surat,
            $surat->id,
            Auth::user(),
            $request->catatan_revisi
        );

        if (!$result['success']) {
            flash()->error($result['message']);
            return redirect()->back();
        }

        // Update status surat kembali ke 'revised'
        $surat->update([
            'status'         => 'revised',
            'catatan_revisi' => $request->catatan_revisi,
        ]);

        flash()->success($result['message']);
        return redirect()->route('surat.show', $surat->id);
    }

    // ── DOWNLOAD ────────────────────────────────────────────
    public function download(Surat $surat)
    {
        $this->authorize('download', $surat);

        if (!$surat->file_pdf) {
            flash()->error('File PDF tidak tersedia.');
            return redirect()->route('surat.show', $surat->id);
        }

        $filePath = storage_path('app/public/' . $surat->file_pdf);

        if (!file_exists($filePath)) {
            flash()->error('File PDF tidak ditemukan di server.');
            return redirect()->route('surat.show', $surat->id);
        }

        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $surat->nomor_surat) . '.pdf';

        return response()->download($filePath, $filename);
    }

    // ── GENERATE NOMOR SURAT ────────────────────────────────
    private function generateNomorSurat(): string
    {
        $count = Surat::whereYear('created_at', now()->year)->count() + 1;
        return sprintf('SURAT/%d/%s/%03d', now()->year, now()->format('m'), $count);
    }
}