<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    protected $fillable = [
        'user_id',
        'surat_type_id',
        'nomor_surat',
        'jenis_surat',
        'perihal',
        'file_pdf',
        'cover_pdf_path',
        'final_pdf_path',
        'status',
        'catatan_revisi',
        'ttd_coordinates',
    ];

    protected $casts = [
        'ttd_coordinates' => 'array',
    ];

    // ── Helper: cek apakah punya final_pdf ─────────────
    public function hasFinalPdf(): bool
    {
        return !empty($this->final_pdf_path);
    }

    // ── Relasi ke User (pembuat) ───────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Relasi ke Jenis Surat ─────────────────────────
    public function suratType()
    {
        return $this->belongsTo(SuratType::class);
    }

    // ── Relasi ke DocumentApproval (log 4 step) ────────
    public function approvals()
    {
        return $this->hasMany(DocumentApproval::class, 'document_id')
            ->orderBy('step_order');
    }

    // ── Helper: ambil step yang sedang waiting ─────────
    public function waitingStep(): ?DocumentApproval
    {
        return $this->approvals()->where('status', 'waiting')->first();
    }

    // ── Helper: cek apakah semua step sudah approved ───
    public function isFullyApproved(): bool
    {
        return $this->approvals()->whereNotIn('status', ['approved'])->doesntExist();
    }

    // ── Helper: cek apakah bisa diedit (oleh pembuat) ──
    public function canBeEdited(): bool
    {
        // Hanya bisa diedit jika status 'revised' (setelah ditolak/perlu revisi)
        // Status 'submitted' tidak bisa diedit lagi
        return $this->status === 'revised';
    }

    // ── Helper: cek apakah bisa dihapus (oleh pembuat) ──
    public function canBeDeleted(): bool
    {
        // Hanya bisa dihapus jika status submitted dan belum ada approval diproses
        if ($this->status !== 'submitted') {
            return false;
        }

        $hasProcessedApproval = $this->approvals()
            ->whereIn('status', ['approved', 'rejected'])
            ->exists();

        return !$hasProcessedApproval;
    }

    // ── Label status untuk tampilan ────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted'      => 'Diajukan',
            'approved_owner' => 'Disetujui Penuh',
            'revised'        => 'Perlu Revisi',
            'rejected'       => 'Ditolak',
            default          => ucfirst($this->status),
        };
    }

    // ── Warna badge per status ─────────────────────────
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'submitted'      => 'b-blue',
            'approved_owner' => 'b-green',
            'revised'        => 'b-amber',
            'rejected'       => 'b-red',
            default          => 'b-gray',
        };
    }
}