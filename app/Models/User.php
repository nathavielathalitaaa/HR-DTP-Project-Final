<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
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
        'avatar',
        'position',    // kolom posisi singkat di users (opsional, bisa pakai profile)
        'department',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'    => 'datetime',
        'password'             => 'hashed',
        'join_date'            => 'date',
        'must_change_password' => 'boolean',
    ];

    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class, 'user_id');
    }

    /**
     * ambil profile, buat baru kalau belum ada.
     * pakai ini di controller/view supaya tidak null.
     * contoh: auth()->user()->profileornew->nik
     */
    public function getProfileOrNewAttribute(): EmployeeProfile
    {
        return $this->profile ?? new EmployeeProfile(['user_id' => $this->id]);
    }

    // ── relasi ke absensi 
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    // ── helper: jabatan dari profile
    public function getJabatanAttribute(): ?string
    {
        return $this->profile?->jabatan;
    }

    // ── helper: cek jabatan untuk sistem approval
    public function hasJabatan(string $jabatan): bool
    {
        return $this->profile?->jabatan === $jabatan;
    }

    // ── helper: URL avatar (S3 / Storage / local fallback)
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=E8F5EE&color=1A2B24&size=200';
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        $disk = Storage::disk(config('filesystems.default'));

        $getUrl = function ($path) use ($disk) {
            return $disk->providesTemporaryUrls()
                ? $disk->temporaryUrl($path, now()->addMinutes(60))
                : $disk->url($path);
        };

        try {
            if ($disk->exists($this->avatar)) {
                return $getUrl($this->avatar);
            }

            if ($disk->exists('avatars/' . $this->avatar)) {
                return $getUrl('avatars/' . $this->avatar);
            }
        } catch (\Throwable $e) {
            return $getUrl($this->avatar);
        }

        if (file_exists(public_path('assets/images/user/' . $this->avatar))) {
            return asset('assets/images/user/' . $this->avatar);
        }

        return $getUrl($this->avatar);
    }
}

