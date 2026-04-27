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
        'nomor_surat',
        'jenis_surat',
        'perihal',
        'file_pdf',
        'cover_pdf_path',
        'status',
        'catatan_revisi',
    ];

    // ── Relasi ke User (pembuat) ───────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Relasi ke DocumentApproval (log 4 step) ────────
   public function approvals()
    {
        return $this->hasMany(DocumentApproval::class, 'document_id')
            ->whereColumn('document_type', \DB::raw("CONCAT('surat_', (SELECT jenis_surat FROM surats WHERE id = document_id))"))
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