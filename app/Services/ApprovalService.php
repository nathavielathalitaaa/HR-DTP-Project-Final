<?php

namespace App\Services;

use App\Models\ApprovalStep;
use App\Models\DocumentApproval;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * ApprovalService
 * Mengelola logika approval dokumen multi-step.
 * Digunakan oleh: SuratController
 */
class ApprovalService
{
    /**
     * Inisialisasi semua step approval saat dokumen disubmit.
     * Dipanggil sekali saat staff submit dokumen.
     *
     * @param  string  $documentType  Contoh: 'surat', 'purchase_requisition'
     * @param  int     $documentId    ID dokumen
     * @return bool    true jika berhasil dibuat
     */
    public function initApproval(string $documentType, int $documentId): bool
    {
        $steps = ApprovalStep::stepsFor($documentType);

        if ($steps->isEmpty()) {
            return false; // jenis dokumen ini tidak punya alur approval
        }

        DB::transaction(function () use ($steps, $documentType, $documentId) {
            foreach ($steps as $index => $step) {
                DocumentApproval::create([
                    'document_type'    => $documentType,
                    'document_id'      => $documentId,
                    'step_order'       => $step->step_order,
                    'jabatan'          => $step->jabatan,
                    'assigned_user_id' => $step->user_id, // copy user spesifik dari template step
                    'label'            => $step->label,
                    'approver_id'      => null,
                    // Step pertama langsung 'waiting', sisanya 'pending'
                    'status'           => $index === 0 ? 'waiting' : 'pending',
                ]);
            }
        });

        return true;
    }

    /**
     * Inisialisasi step approval dari SuratType.
     */
    public function initFromSuratType(\App\Models\Surat $surat): bool
    {
        $suratType = $surat->suratType;
        if (!$suratType) return false;

        $approvers = $suratType->approvers;
        if ($approvers->isEmpty()) return false;

        DB::transaction(function () use ($approvers, $surat) {
            foreach ($approvers as $index => $approver) {
                DocumentApproval::create([
                    'document_type'    => 'surat_' . $surat->suratType->kode,
                    'document_id'      => $surat->id,
                    'step_order'       => $approver->urutan,
                    'jabatan'          => $approver->jabatan_label,
                    'assigned_user_id' => $approver->user_id,
                    'label'            => $approver->label,
                    'metode_ttd'       => $approver->metode_ttd,
                    'approver_id'      => null,
                    'status'           => $index === 0 ? 'waiting' : 'pending',
                ]);
            }
        });

        return true;
    }

    /**
     * Approve step yang sedang 'waiting'.
     * Otomatis aktivasi step berikutnya jika ada.
     *
     * @param  string  $documentType
     * @param  int     $documentId
     * @param  User    $approver      User yang melakukan approve
     * @param  string  $catatan       Opsional
     * @param  string  $ttdSnapshot   Opsional — path TTD untuk disimpan
     * @return array   ['success' => bool, 'message' => string, 'selesai' => bool]
     */
    public function approve(string $documentType, int $documentId, User $approver, string $catatan = '', ?string $ttdSnapshot = null): array
    {
        // Ambil step yang sedang waiting
        $currentStep = DocumentApproval::forDocument($documentType, $documentId)
            ->where('status', 'waiting')
            ->first();

        if (!$currentStep) {
            return ['success' => false, 'message' => 'Tidak ada step yang menunggu approval.', 'selesai' => false];
        }

        // Cek apakah user ini berhak approve step ini
        if (!$this->isUserAllowedForStep($currentStep, $approver)) {
            return [
                'success' => false,
                'message' => "Bukan giliran Anda untuk approve. Step ini untuk {$currentStep->label} (jabatan: {$currentStep->jabatan}).",
                'selesai' => false,
            ];
        }

        DB::transaction(function () use ($currentStep, $approver, $catatan, $documentType, $documentId, $ttdSnapshot) {
            // Tandai step ini approved
            $currentStep->update([
                'status'       => 'approved',
                'approver_id'  => $approver->id,
                'catatan'      => $catatan,
                'actioned_at'  => now(),
                'ttd_snapshot' => $ttdSnapshot,
            ]);

            // Aktivasi step berikutnya
            $nextStep = DocumentApproval::forDocument($documentType, $documentId)
                ->where('step_order', $currentStep->step_order + 1)
                ->where('status', 'pending')
                ->first();

            if ($nextStep) {
                $nextStep->update(['status' => 'waiting']);
            }
        });

        // Cek apakah semua step sudah approved (dokumen selesai)
        $sisaPending = DocumentApproval::forDocument($documentType, $documentId)
            ->whereIn('status', ['pending', 'waiting'])
            ->count();

        $selesai = $sisaPending === 0;

        return [
            'success' => true,
            'message' => $selesai
                ? 'Semua approval selesai. Dokumen telah disetujui penuh.'
                : "Step {$currentStep->label} disetujui. Menunggu approval berikutnya.",
            'selesai' => $selesai,
        ];
    }

    /**
     * Reject step — dokumen kembali ke staff untuk direvisi.
     * Semua step direset ke 'pending', step pertama kembali ke 'waiting'.
     *
     * @param  string  $documentType
     * @param  int     $documentId
     * @param  User    $approver
     * @param  string  $catatan   Wajib diisi saat reject
     * @return array
     */
    public function reject(string $documentType, int $documentId, User $approver, string $catatan): array
    {
        $currentStep = DocumentApproval::forDocument($documentType, $documentId)
            ->where('status', 'waiting')
            ->first();

        if (!$currentStep) {
            return ['success' => false, 'message' => 'Tidak ada step yang aktif.', 'selesai' => false];
        }

        // Cek apakah user ini berhak reject step ini
        if (!$this->isUserAllowedForStep($currentStep, $approver)) {
            return [
                'success' => false,
                'message' => "Bukan giliran Anda untuk menolak. Step ini untuk {$currentStep->label} (jabatan: {$currentStep->jabatan}).",
                'selesai' => false,
            ];
        }

        DB::transaction(function () use ($currentStep, $approver, $catatan, $documentType, $documentId) {
            // Tandai step ini rejected
            $currentStep->update([
                'status'      => 'rejected',
                'approver_id' => $approver->id,
                'catatan'     => $catatan,
                'actioned_at' => now(),
            ]);

            // Reset semua step: pending semua, aktifkan step pertama lagi
            // (siap jika staff resubmit setelah revisi)
            DocumentApproval::forDocument($documentType, $documentId)
                ->where('status', 'pending')
                ->update(['status' => 'pending']); // sudah pending, tidak perlu diubah

            // Langsung hapus semua log dan init ulang saat staff resubmit
            // (dihandle di resubmit method)
        });

        return [
            'success' => true,
            'message' => "Dokumen ditolak oleh {$currentStep->label}. Kembali ke staff untuk revisi.",
            'selesai' => false,
        ];
    }

    /**
     * Staff resubmit setelah revisi.
     * Hapus semua log lama, inisialisasi ulang dari step 1.
     */
    public function resubmit(string $documentType, int $documentId): bool
    {
        DB::transaction(function () use ($documentType, $documentId) {
            // Hapus semua log approval lama
            DocumentApproval::forDocument($documentType, $documentId)->delete();

            // Init ulang
            if (str_starts_with($documentType, 'surat_')) {
                $surat = \App\Models\Surat::find($documentId);
                if ($surat) {
                    $this->initFromSuratType($surat);
                }
            } else {
                $this->initApproval($documentType, $documentId);
            }
        });

        return true;
    }

    /**
     * Ambil status approval lengkap untuk ditampilkan di view.
     * Return collection berurutan step 1 → step terakhir.
     */
    public function getStatus(string $documentType, int $documentId)
    {
        return DocumentApproval::forDocument($documentType, $documentId)->get();
    }

    /**
     * Cek apakah user saat ini bisa approve dokumen ini.
     */
    public function canApprove(string $documentType, int $documentId, User $user): bool
    {
        $waitingStep = DocumentApproval::forDocument($documentType, $documentId)
            ->where('status', 'waiting')
            ->first();

        if (!$waitingStep) return false;

        return $this->isUserAllowedForStep($waitingStep, $user);
    }

    /**
     * Cek apakah user berhak mengaksi step tertentu.
     * Jika step punya assigned_user_id → hanya user itu saja yang boleh.
     * Jika tidak → cek jabatan.
     */
    private function isUserAllowedForStep(DocumentApproval $step, User $user): bool
    {
        // Jika step ditunjuk ke user spesifik, hanya user itu yang bisa approve
        if ($step->assigned_user_id) {
            return (int) $step->assigned_user_id === (int) $user->id;
        }

        $userRole = strtolower($user->role_name ?? '');
        
        // Super admin bisa approve semuanya
        if ($userRole === 'super-admin') {
            return true;
        }

        $jabatanUser = strtolower($user->profile?->jabatan ?? '');
        $jabatanStep = strtolower($step->jabatan ?? '');

        if ($jabatanUser === $jabatanStep) {
            return true;
        }

        // Khusus: Jika step ditujukan untuk jabatan "hr", izinkan user dengan role 'hr' atau 'supervisor'
        if ($jabatanStep === 'hr' && in_array($userRole, ['hr', 'supervisor'])) {
            return true;
        }

        return false;
    }

    /**
     * Ambil step yang sedang menunggu (untuk notifikasi / dashboard).
     */
    public function getWaitingStep(string $documentType, int $documentId): ?DocumentApproval
    {
        return DocumentApproval::forDocument($documentType, $documentId)
            ->where('status', 'waiting')
            ->first();
    }

    /**
     * Tandai dokumen sebagai sudah dibaca untuk step yang sedang aktif.
     */
    public function markAsRead(string $documentType, int $documentId, User $user): void
    {
        $waitingStep = $this->getWaitingStep($documentType, $documentId);

        if ($waitingStep && $this->isUserAllowedForStep($waitingStep, $user)) {
            $waitingStep->update(['is_read' => true]);
        }
    }
}