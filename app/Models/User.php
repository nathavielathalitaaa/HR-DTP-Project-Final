<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'role_name',
        'status',
        'phone_number',
        'location',
        'join_date',
        'gaji_pokok',
        'avatar',
        'position',    // kolom posisi singkat di users (opsional, bisa pakai profile)
        'department',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'join_date'         => 'date',
    ];

    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class, 'user_id');
    }

    /**
     * Ambil profile, buat baru kalau belum ada.
     * Pakai ini di controller/view supaya tidak null.
     * Contoh: auth()->user()->profileOrNew->nik
     */
    public function getProfileOrNewAttribute(): EmployeeProfile
    {
        return $this->profile ?? new EmployeeProfile(['user_id' => $this->id]);
    }

    // ── Relasi ke Absensi 
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    // ── Relasi ke JadwalShift 
    public function jadwalShifts()
    {
        return $this->hasMany(JadwalShift::class, 'user_id');
    }

    // ── Relasi ke Penggajian 
    public function penggajians()
    {
        return $this->hasMany(Penggajian::class, 'user_id');
    }

    // ── Relasi ke Leave 
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'staff_id');
    }

    // ── Helper: jabatan dari profile
    public function getJabatanAttribute(): ?string
    {
        return $this->profile?->jabatan;
    }

    // ── Helper: cek jabatan untuk sistem approval
    public function hasJabatan(string $jabatan): bool
    {
        return $this->profile?->jabatan === $jabatan;
    }
}