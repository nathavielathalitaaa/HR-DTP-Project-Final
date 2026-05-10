<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratType extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'nomor_format',
        'nomor_counter',
        'nomor_reset',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'nomor_format' => 'array',
        'is_active' => 'boolean',
    ];

    public function approvers()
    {
        return $this->hasMany(SuratTypeApprover::class)->orderBy('urutan');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }
}
