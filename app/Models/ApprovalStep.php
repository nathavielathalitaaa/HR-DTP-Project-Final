<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStep extends Model
{
    protected $fillable = [
        'document_type',
        'step_order',
        'jabatan',
        'label',
    ];

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