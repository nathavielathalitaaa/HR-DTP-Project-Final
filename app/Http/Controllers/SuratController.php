<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Services\ApprovalService;
use App\Services\PinVerificationService;
use App\Services\ApprovalCoverService;
use App\Services\PdfStampService;
use App\Services\NotificationService;
use App\Services\SuratNumberService;
use App\Models\SuratType;
use App\Models\ApprovalStep;
use App\Models\EmployeeProfile;
use App\Http\Requests\Surat\StoreSuratRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function __construct(
        private ApprovalService $approval,
        private PinVerificationService $pinService,
        private ApprovalCoverService $coverService,
        private PdfStampService $stampService,
        private NotificationService $notifService,
        private SuratNumberService $numberService,
    ) {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the documents.
     * Filters visibility based on user roles and positions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user()->load('profile');
        $query = Surat::with(['user', 'approvals', 'suratType']);

        // ── Pengecekan Otorisasi Data ───────────────────────────────────
        if (!$user->hasAnyRole(['hr', 'admin', 'super-admin'])) {
            $jabatan = $user->profile?->jabatan;
            
            $query->where(function($q) use ($jabatan, $user) {
                $q->where('user_id', $user->id);

                $q->orWhereHas('approvals', function ($sq) use ($jabatan, $user) {
                    $sq->where('document_type', 'LIKE', 'surat_%')
                      ->where(function($ssq) use ($jabatan, $user) {
                          $ssq->where('assigned_user_id', $user->id);
                          if ($jabatan) {
                              $ssq->orWhere(function($sssq) use ($jabatan) {
                                  $sssq->whereNull('assigned_user_id')
                                       ->where('jabatan', $jabatan);
                              });
                          }
                      });
                });
            });
        }

        $surats = $query->latest()->paginate(15);

        return view('surat.index', compact('surats'));
    }


    /**
     * Show the form for creating a new document.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Surat::class);
        return view('surat.create');
    }


    /**
     * Store a newly created document in storage.
     *
     * @param StoreSuratRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSuratRequest $request)
    {
        // ── Otorisasi ───────────────────────────────────────────────────
        $this->authorize('store', Surat::class);

        $suratType = SuratType::findOrFail($request->surat_type_id);

        // ── Proses Upload File ──────────────────────────────────────────
        $fileName = null;
        
        if ($request->hasFile('file_pdf')) {
            $fileName = $request->file('file_pdf')->store('surat');
        }

        // ── Penyimpanan Data ────────────────────────────────────────────
        $surat = Surat::create([
            'user_id'         => Auth::id(),
            'surat_type_id'   => $suratType->id,
            'nomor_surat'     => $this->numberService->generate($suratType),
            'jenis_surat'     => $suratType->kode,
            'perihal'         => $request->perihal,
            'file_pdf'        => $fileName,
            'ttd_coordinates' => $request->ttd_coordinates ? json_decode($request->ttd_coordinates, true) : null,
            'status'          => 'submitted',
        ]);

        $this->approval->initFromSuratType($surat);

        // ── Pengiriman Notifikasi ───────────────────────────────────────
        $firstStep = $surat->approvals()->where('status', 'waiting')->first();
        
        if ($firstStep) {
            $this->notifService->sendToJabatan(
                $firstStep->jabatan,
                'Permintaan Approval Surat Baru',
                "Karyawan " . Auth::user()->name . " mengajukan surat baru ({$surat->nomor_surat}).",
                route('surat.show', $surat->id)
            );
        }

        flash()->success('Surat berhasil dibuat dan dikirim untuk approval.');
        return redirect()->route('surat.show', $surat->id);
    }


    /**
     * Display the specified document.
     *
     * @param Surat $surat
     * @return \Illuminate\View\View
     */
    public function show(Surat $surat)
    {
        // ── Otorisasi ───────────────────────────────────────────────────
        $this->authorize('view', $surat);

        // ── Pengambilan Data Approval ───────────────────────────────────
        $documentType = 'surat_' . $surat->jenis_surat;
        $steps = $this->approval->getStatus($documentType, $surat->id);
        $authUser = Auth::user()->load('profile');
        $canApprove = $this->approval->canApprove($documentType, $surat->id, $authUser);
        $waitingStep = $this->approval->getWaitingStep($documentType, $surat->id);
        
        $this->approval->markAsRead($documentType, $surat->id, $authUser);

        return view('surat.show', compact('surat', 'steps', 'canApprove', 'waitingStep'));
    }


    /**
     * Show the form for editing the specified document.
     *
     * @param Surat $surat
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Surat $surat)
    {
        // ── Otorisasi & Validasi Status ─────────────────────────────────
        $this->authorize('edit', $surat);

        if (!$surat->canBeEdited()) {
            flash()->error('Surat sudah dalam proses approval dan tidak dapat diubah.');
            return redirect()->route('surat.show', $surat->id);
        }

        return view('surat.edit', compact('surat'));
    }


    /**
     * Update the specified document in storage and resubmit for approval.
     *
     * @param StoreSuratRequest $request
     * @param Surat $surat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreSuratRequest $request, Surat $surat)
    {
        // ── Otorisasi & Validasi Status ─────────────────────────────────
        $this->authorize('update', $surat);

        if (!$surat->canBeEdited()) {
            flash()->error('Surat sudah dalam proses approval dan tidak dapat diubah.');
            return redirect()->route('surat.show', $surat->id);
        }

        // ── Penggantian File PDF ────────────────────────────────────────
        if ($request->hasFile('file_pdf')) {
            if ($surat->file_pdf) {
                Storage::disk(config('filesystems.default'))->delete($surat->file_pdf);
                if (file_exists(storage_path('app/public/' . $surat->file_pdf))) {
                    @unlink(storage_path('app/public/' . $surat->file_pdf));
                }
            }
            
            $surat->update(['file_pdf' => $request->file('file_pdf')->store('surat')]);
        }

        // ── Reset Status & Approval ─────────────────────────────────────
        $surat->update([
            'status'         => 'submitted',
            'catatan_revisi' => null,
        ]);

        $this->approval->resubmit('surat_' . $surat->jenis_surat, $surat->id);

        flash()->success('Surat berhasil direvisi dan dikirim ulang untuk approval.');
        return redirect()->route('surat.show', $surat->id);
    }


    /**
     * Approve the document at the current step.
     *
     * @param Request $request
     * @param Surat $surat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, Surat $surat)
    {
        // ── Validasi Request ────────────────────────────────────────────
        $request->validate([
            'catatan' => 'nullable|string|max:500',
            'pin'     => 'required|string',
        ], [
            'pin.required' => 'PIN wajib diisi untuk menyetujui surat.',
        ]);

        $user = Auth::user()->load('profile');

        if (!$this->pinService->verify($user, $request->pin)) {
            flash()->error('PIN salah. Silakan coba lagi.');
            return redirect()->back();
        }

        // ── Pengecekan Otorisasi Approval ───────────────────────────────
        $documentType = 'surat_' . $surat->jenis_surat;
        
        if (!$this->approval->canApprove($documentType, $surat->id, $user)) {
            flash()->error('Bukan giliran Anda untuk approve surat ini.');
            return redirect()->back();
        }

        // ── Proses Approval ─────────────────────────────────────────────
        $ttdSnapshot = $this->pinService->getTtdPath($user);

        $approvalResult = $this->approval->approve(
            $documentType,
            $surat->id,
            $user,
            $request->catatan ?? '',
            $ttdSnapshot
        );

        if (!$approvalResult['success']) {
            flash()->error($approvalResult['message']);
            return redirect()->back();
        }

        // ── Penanganan Penyelesaian Approval ────────────────────────────
        if ($approvalResult['selesai']) {
            $surat->update(['status' => 'approved_owner']);

            $this->notifService->send(
                $surat->user_id,
                'Surat Disetujui Penuh',
                "Surat dengan nomor {$surat->nomor_surat} telah disetujui sepenuhnya.",
                route('surat.show', $surat->id)
            );

            try {
                $documentType = 'surat_' . $surat->jenis_surat;
                
                if ($surat->suratType) {
                    $ttdMode = 'stamp';
                } else {
                    $step = ApprovalStep::where('document_type', $documentType)->first();
                    $ttdMode = $step?->ttd_mode ?? 'append';
                }

                if ($ttdMode === 'stamp') {
                    $finalPath = $this->stampService->stamp($surat);
                    $surat->update(['final_pdf_path' => $finalPath]);
                } else {
                    $coverPath = $this->coverService->generateCover($surat);
                    $surat->update(['cover_pdf_path' => $coverPath]);
                    $surat->refresh();
                    
                    $finalPath = $this->coverService->processMerge($surat);
                    
                    if ($finalPath) {
                        $surat->update(['final_pdf_path' => $finalPath]);
                        $surat->refresh();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Gagal generate cover/stamp/merge PDF: ' . $e->getMessage());
                flash()->warning('Surat disetujui, namun PDF dengan tanda tangan gagal digabungkan: ' . $e->getMessage());
            }
        } else {
            // ── Notifikasi Approver Berikutnya ──────────────────────────
            $nextStep = $surat->waitingStep();
            
            if ($nextStep) {
                $this->notifService->sendToJabatan(
                    $nextStep->jabatan,
                    'Menunggu Approval Surat',
                    "Ada surat baru ({$surat->nomor_surat}) yang menunggu approval Anda.",
                    route('surat.show', $surat->id)
                );
            }
        }

        flash()->success($approvalResult['message']);
        return redirect()->route('surat.show', $surat->id);
    }


    /**
     * Reject the document and return it to the author for revision.
     *
     * @param Request $request
     * @param Surat $surat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Surat $surat)
    {
        // ── Validasi Request ────────────────────────────────────────────
        $request->validate([
            'catatan_revisi' => 'required|string|min:5|max:500',
        ], [
            'catatan_revisi.required' => 'Catatan revisi wajib diisi saat menolak.',
            'catatan_revisi.min'      => 'Catatan revisi minimal 5 karakter.',
        ]);

        // ── Pengecekan Otorisasi Rejection ──────────────────────────────
        $user = Auth::user()->load('profile');
        $documentType = 'surat_' . $surat->jenis_surat;
        
        if (!$this->approval->canApprove($documentType, $surat->id, $user)) {
            flash()->error('Bukan giliran Anda untuk menolak surat ini.');
            return redirect()->back();
        }

        // ── Proses Rejection ────────────────────────────────────────────
        $approvalResult = $this->approval->reject(
            $documentType,
            $surat->id,
            $user,
            $request->catatan_revisi
        );

        if (!$approvalResult['success']) {
            flash()->error($approvalResult['message']);
            return redirect()->back();
        }

        $this->notifService->send(
            $surat->user_id,
            'Surat Ditolak / Perlu Revisi',
            "Surat dengan nomor {$surat->nomor_surat} ditolak oleh " . Auth::user()->name . ". Silakan cek catatan revisi.",
            route('surat.show', $surat->id)
        );

        $surat->update([
            'status'         => 'revised',
            'catatan_revisi' => $request->catatan_revisi,
        ]);

        flash()->success($approvalResult['message']);
        return redirect()->route('surat.show', $surat->id);
    }


    /**
     * Download the specified document file.
     *
     * @param Surat $surat
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(Surat $surat, Request $request)
    {
        // ── Otorisasi ───────────────────────────────────────────────────
        $this->authorize('download', $surat);

        $type = $request->query('type', 'auto');

        // ── Penentuan Path File ─────────────────────────────────────────
        $relativePath = match($type) {
            'original' => $surat->file_pdf,
            'cover'    => $surat->cover_pdf_path,
            'final'    => $surat->final_pdf_path,
            default    => $surat->final_pdf_path ?? $surat->cover_pdf_path ?? $surat->file_pdf
        };

        if (!$relativePath) {
            flash()->error('File PDF tidak tersedia.');
            return redirect()->route('surat.show', $surat->id);
        }

        // ── Formatting Nama File ────────────────────────────────────────
        $baseName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $surat->nomor_surat);
        
        $suffix = '';
        if ($type === 'original') {
            $suffix = '_original';
        } elseif ($type === 'cover') {
            $suffix = '_approval_sheet';
        } elseif (($surat->hasFinalPdf() && ($type === 'auto' || $type === 'final')) || ($surat->cover_pdf_path && $type === 'cover')) {
            $suffix = '_signed';
        }

        $filename = $baseName . $suffix . '.pdf';

        $disk = Storage::disk(config('filesystems.default'));
        if ($disk->exists($relativePath)) {
            return $disk->download($relativePath, $filename);
        }

        $filePath = storage_path('app/public/' . $relativePath);

        if (file_exists($filePath)) {
            return response()->download($filePath, $filename);
        }

        flash()->error('File PDF tidak ditemukan di server.');
        return redirect()->route('surat.show', $surat->id);
    }


    /**
     * Remove the specified document from storage.
     *
     * @param Surat $surat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Surat $surat)
    {
        // ── Otorisasi & Validasi ────────────────────────────────────────
        if (Auth::id() !== $surat->user_id) {
            abort(403);
        }

        if (!$surat->canBeDeleted()) {
            flash()->error('Surat sudah dalam proses approval dan tidak dapat dihapus.');
            return redirect()->route('surat.show', $surat->id);
        }

        // ── Proses Penghapusan File & Data ──────────────────────────────
        $filesToDelete = [
            $surat->file_pdf,
            $surat->cover_pdf_path,
            $surat->final_pdf_path
        ];

        foreach ($filesToDelete as $file) {
            if ($file) {
                Storage::disk(config('filesystems.default'))->delete($file);
                if (file_exists(storage_path('app/public/' . $file))) {
                    @unlink(storage_path('app/public/' . $file));
                }
            }
        }

        $surat->delete();

        flash()->success('Surat berhasil dihapus.');
        return redirect()->route('surat.index');
    }


    /**
     * Get the signature mode and approvers for a document type.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTtdMode(Request $request)
    {
        $jenis = $request->jenis_surat;
        
        $suratType = SuratType::where('kode', $jenis)->first();
        
        if ($suratType) {
            $approvers = $suratType->approvers->map(function($a) {
                return [
                    'jabatan' => $a->jabatan_label,
                    'label'   => $a->label,
                ];
            })->toArray();

            return response()->json([
                'mode' => 'stamp',
                'approvers' => $approvers
            ]);
        }

        $step = ApprovalStep::where('document_type', 'surat_' . $jenis)
            ->orWhere('document_type', $jenis)
            ->first();

        $approvers = ApprovalStep::where('document_type', 'surat_' . $jenis)
            ->orWhere('document_type', $jenis)
            ->orderBy('step_order')
            ->get()
            ->map(function($s) {
                return [
                    'jabatan' => $s->jabatan,
                    'label'   => $s->label,
                ];
            })
            ->toArray();

        return response()->json([
            'mode' => $step?->ttd_mode ?? 'append',
            'approvers' => $approvers
        ]);
    }


    /**
     * Provide a preview of the signature image for a specific role.
     *
     * @param string $jabatan
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getTtdPreview(string $jabatan)
    {
        $profile = EmployeeProfile::where('jabatan', $jabatan)
            ->whereNotNull('ttd_path')
            ->first();

        if (!$profile) {
            return response('', 204);
        }

        $paths = [
            storage_path('app/private/private/' . $profile->ttd_path),
            storage_path('app/private/' . $profile->ttd_path),
        ];

        $path = null;
        
        foreach ($paths as $p) {
            if (file_exists($p)) {
                $path = $p;
                break;
            }
        }

        if (!$path) {
            return response('', 204);
        }

        return response()->file($path, [
            'Content-Type'  => mime_content_type($path),
            'Cache-Control' => 'private, max-age=300',
        ]);
    }
}