<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTypeApprover extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_type_id',
        'user_id',
        'urutan',
        'jabatan_label',
        'label',
        'metode_ttd',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function suratType()
    {
        return $this->belongsTo(SuratType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
