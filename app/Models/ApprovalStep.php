<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStep extends Model
{
    protected $fillable = [
        'document_type',
        'step_order',
        'jabatan',
        'user_id',       // approver spesifik (nullable — jika null, semua dengan jabatan ini bisa approve)
        'label',
        'ttd_mode',
        'ttd_coordinates',
        'setting_overrides',
    ];

    protected $casts = [
        'ttd_coordinates' => 'array',
        'setting_overrides' => 'array',
    ];

    public function isModeStamp(): bool
    {
        return $this->ttd_mode === 'stamp';
    }

    public function isModeAppend(): bool
    {
        return $this->ttd_mode === 'append';
    }

    /**
     * Relasi ke user spesifik yang ditunjuk sebagai approver step ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil semua step untuk satu jenis dokumen, urut.
     */
    public static function stepsFor(string $documentType)
    {
        return static::where('document_type', $documentType)
            ->orderBy('step_order')
            ->get();
    }

    /**
     * Cek apakah jenis dokumen ini punya approval step sama sekali.
     */
    public static function hasApproval(string $documentType): bool
    {
        return static::where('document_type', $documentType)->exists();
    }
}