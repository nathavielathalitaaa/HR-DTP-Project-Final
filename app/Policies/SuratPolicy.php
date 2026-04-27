<?php

namespace App\Policies;

use App\Models\Surat;
use App\Models\User;

class SuratPolicy
{
    // Siapa saja bisa lihat list (difilter di controller)
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Lihat detail: staff hanya miliknya, approver sesuai jabatan, hr semua
    public function view(User $user, Surat $surat): bool
    {
        if ($user->hasRole('staff')) {
            return $user->id === $surat->user_id;
        }
        // Supervisor/hr dengan jabatan approval bisa lihat semua surat
        if ($user->profile?->jabatan) {
            return true;
        }
        return $user->hasRole('hr');
    }

    // Semua role bisa buat surat
    public function create(User $user): bool
    {
        return true;
    }

    public function store(User $user): bool
    {
        return true;
    }

    // Hanya staff pemilik surat yang berstatus 'revised'
    public function edit(User $user, Surat $surat): bool
    {
        return $user->hasRole('staff')
            && $user->id === $surat->user_id
            && $surat->status === 'revised';
    }

    public function update(User $user, Surat $surat): bool
    {
        return $user->hasRole('staff')
            && $user->id === $surat->user_id
            && $surat->status === 'revised';
    }

    // Download: staff hanya miliknya, siapapun dengan jabatan approval, hr semua
    public function download(User $user, Surat $surat): bool
    {
        if ($user->hasRole('staff')) {
            return $user->id === $surat->user_id;
        }
        if ($user->profile?->jabatan) {
            return true;
        }
        return $user->hasRole('hr');
    }
}