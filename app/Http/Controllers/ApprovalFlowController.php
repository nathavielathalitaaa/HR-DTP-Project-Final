<?php

namespace App\Http\Controllers;

use App\Models\ApprovalStep;
use App\Models\DocumentApproval;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApprovalFlowController extends Controller
{
    /**
     * Jenis surat yang valid di sistem
     */
    private const DOCUMENT_TYPES = [
        'surat_izin',
        'surat_permohonan',
        'surat_resign',
        'surat_tugas',
        'surat_rekomendasi',
        'surat_lainnya',
    ];

    /**
     * Jabatan yang valid
     */
    private const VALID_JABATAN = [
        'hod',
        'hr',
        'purchasing',
        'owner_rep',
        'direktur',
    ];

    /**
     * Label yang sesuai dengan jabatan
     */
    private const LABEL_MAPPING = [
        'hod' => 'Head of Department',
        'hr' => 'Human Resources',
        'purchasing' => 'Purchasing',
        'owner_rep' => 'Owner Representative',
        'direktur' => 'Direktur',
    ];

    /**
     * Display semua jenis surat + approval steps mereka
     */
    public function index()
    {
        $approvalFlows = [];

        foreach (self::DOCUMENT_TYPES as $docType) {
            $approvalFlows[$docType] = [
                'display_name' => $this->formatDocumentTypeName($docType),
                'steps' => ApprovalStep::where('document_type', $docType)
                    ->orderBy('step_order')
                    ->get(),
            ];
        }

        // Get waiting approvals
        $waitingApprovals = DocumentApproval::where('status', 'waiting')
            ->where('document_type', 'LIKE', 'surat_%')
            ->with('approver')
            ->get()
            ->map(function ($approval) {
                // Get the Surat
                $surat = Surat::find($approval->document_id);
                $approval->surat = $surat;
                return $approval;
            });

        return view('hr.approval-flow.index', compact('approvalFlows', 'waitingApprovals'));
    }

    /**
     * Tambah step baru ke jenis surat tertentu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => [
                'required',
                'string',
                Rule::in(self::DOCUMENT_TYPES),
            ],
            'jabatan' => [
                'required',
                Rule::in(self::VALID_JABATAN),
            ],
            'label' => 'required|string|max:100',
        ], [
            'document_type.required' => 'Jenis surat harus dipilih',
            'document_type.in' => 'Jenis surat tidak valid',
            'jabatan.required' => 'Jabatan harus dipilih',
            'jabatan.in' => 'Jabatan tidak valid',
            'label.required' => 'Label harus diisi',
            'label.max' => 'Label maksimal 100 karakter',
        ]);

        // Hitung step_order otomatis
        $maxStepOrder = ApprovalStep::where('document_type', $validated['document_type'])
            ->max('step_order') ?? 0;

        $validated['step_order'] = $maxStepOrder + 1;

        ApprovalStep::create($validated);

        flash()->success('Step approval berhasil ditambahkan');
        return redirect()->back();
    }

    /**
     * Hapus satu step berdasarkan ID
     * (hanya boleh jika tidak ada surat aktif dengan status waiting/pending untuk step ini)
     */
    public function destroy($id)
    {
        $step = ApprovalStep::findOrFail($id);

        // Cek apakah ada DocumentApproval dengan status waiting/pending
        $activeApproval = DocumentApproval::where('document_type', $step->document_type)
            ->where('jabatan', $step->jabatan)
            ->whereIn('status', ['waiting', 'pending'])
            ->exists();

        if ($activeApproval) {
            flash()->error('Tidak dapat menghapus step karena ada surat yang sedang dalam proses approval.');
            return redirect()->back();
        }

        $step->delete();

        // Reorder steps untuk document_type ini
        $this->reorderStepsForDocumentType($step->document_type);

        flash()->success('Step approval berhasil dihapus');
        return redirect()->back();
    }

    /**
     * Update urutan steps untuk satu document_type
     * Menerima array: ['steps' => [['id'=>1,'step_order'=>1], ...]]
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'document_type' => [
                'required',
                'string',
                Rule::in(self::DOCUMENT_TYPES),
            ],
            'steps' => 'required|array',
            'steps.*.id' => 'required|integer',
            'steps.*.step_order' => 'required|integer|min:1',
        ]);

        foreach ($validated['steps'] as $stepData) {
            ApprovalStep::where('id', $stepData['id'])
                ->where('document_type', $validated['document_type'])
                ->update(['step_order' => $stepData['step_order']]);
        }

        flash()->success('Urutan step berhasil diperbarui');
        return redirect()->back();
    }

    /**
     * Helper: Reorder steps otomatis setelah delete (agar tidak ada gap)
     */
    private function reorderStepsForDocumentType(string $documentType)
    {
        $steps = ApprovalStep::where('document_type', $documentType)
            ->orderBy('step_order')
            ->get();

        foreach ($steps as $index => $step) {
            $step->update(['step_order' => $index + 1]);
        }
    }

    /**
     * Format document type menjadi display name yang readable
     * Contoh: 'surat_izin' => 'Surat Izin'
     */
    private function formatDocumentTypeName(string $docType): string
    {
        return ucwords(str_replace('_', ' ', $docType));
    }

    /**
     * Get label mapping (untuk view)
     */
    public static function getLabelMapping()
    {
        return self::LABEL_MAPPING;
    }

    /**
     * Get valid jabatan (untuk view)
     */
    public static function getValidJabatan()
    {
        return self::VALID_JABATAN;
    }

    /**
     * Reassign/Delegate step approval ke jabatan lain
     * Hanya untuk DocumentApproval dengan status 'waiting'
     */
    public function reassign(Request $request)
    {
        $validated = $request->validate([
            'document_approval_id' => 'required|exists:document_approvals,id',
            'jabatan_baru' => [
                'required',
                Rule::in(self::VALID_JABATAN),
            ],
            'label_baru' => 'required|string|max:100',
        ], [
            'document_approval_id.required' => 'Document approval harus dipilih',
            'document_approval_id.exists' => 'Document approval tidak ditemukan',
            'jabatan_baru.required' => 'Jabatan baru harus dipilih',
            'jabatan_baru.in' => 'Jabatan baru tidak valid',
            'label_baru.required' => 'Label baru harus diisi',
            'label_baru.max' => 'Label baru maksimal 100 karakter',
        ]);

        $documentApproval = DocumentApproval::findOrFail($validated['document_approval_id']);

        // Validasi: hanya bisa reassign jika status 'waiting'
        if ($documentApproval->status !== 'waiting') {
            flash()->error('Hanya bisa mengubah step yang sedang menunggu (waiting).');
            return redirect()->back();
        }

        // Update jabatan, label, dan reset approver_id
        $documentApproval->update([
            'jabatan' => $validated['jabatan_baru'],
            'label' => $validated['label_baru'],
            'approver_id' => null,
        ]);

        flash()->success("Step approval berhasil dialihkan ke {$validated['label_baru']}.");
        return redirect()->back();
    }

    /**
     * Tampilkan riwayat audit approval (approved & rejected)
     */
    public function auditLog(Request $request)
    {
        $logs = DocumentApproval::whereIn('status', ['approved', 'rejected'])
            ->where('document_type', 'LIKE', 'surat_%')
            ->with('approver')
            ->orderByDesc('actioned_at')
            ->paginate(20);

        // Attach surat untuk setiap log
        $logs->getCollection()->transform(function ($log) {
            $log->surat = Surat::find($log->document_id);
            return $log;
        });

        return view('hr.approval-flow.audit', compact('logs'));
    }
}
