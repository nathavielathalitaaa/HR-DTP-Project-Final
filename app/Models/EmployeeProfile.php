<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $table = 'employee_profiles';

    protected $fillable = [
        'user_id',
        // Identitas
        'nik',
        'no_kk',
        'npwp',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        // Kepegawaian
        'jabatan',
        'pendidikan_terakhir',
        'tgl_bergabung',
        'tgl_kontrak_akhir',
        // Keluarga
        'status_pernikahan',
        'jumlah_anak',
        // Kontak
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        // Approval
        'ttd_path',
        'pin',
    ];

    protected $hidden = [
        'pin', // jangan pernah expose PIN
    ];

    protected $casts = [
        'tgl_bergabung'     => 'date',
        'tgl_kontrak_akhir' => 'date',
        'jumlah_anak'       => 'integer',
    ];

    // ── Relasi ke User ─────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helper: cek PIN ────────────────────────────────
    public function checkPin(string $pin): bool
    {
        return Hash::check($pin, $this->pin);
    }

    // ── Helper: set PIN (auto hash) ────────────────────
    public function setPin(string $pin): void
    {
        $this->update(['pin' => Hash::make($pin)]);
    }

    // ── Label status pernikahan ────────────────────────
    public function getStatusPernikahanLabelAttribute(): string
    {
        return match ($this->status_pernikahan) {
            'belum_menikah' => 'Belum Menikah',
            'menikah'       => 'Menikah',
            'cerai_hidup'   => 'Cerai Hidup',
            'cerai_mati'    => 'Cerai Mati',
            default         => '-',
        };
    }

    // ── Label pendidikan ───────────────────────────────
    public static function pendidikanOptions(): array
    {
        return ['SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'];
    }

    // ── Label jabatan approval ─────────────────────────
    public static function jabatanApprovalOptions(): array
    {
        return [
            'hod'       => 'Head of Department (HOD)',
            'purchasing' => 'Purchasing',
            'owner_rep' => 'Owner Representative',
            'direktur'  => 'Direktur',
        ];
    }
}