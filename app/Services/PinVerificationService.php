<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PinVerificationService
{
    /**
     * Verifikasi PIN user.
     * PIN disimpan sebagai bcrypt hash di employee_profiles.pin
     */
    public function verify(User $user, string $pin): bool
    {
        $profile = $user->profile;
        if (!$profile || !$profile->pin) {
            return false;
        }
        return Hash::check($pin, $profile->pin);
    }

    /**
     * Ambil path TTD user untuk disimpan sebagai snapshot.
     */
    public function getTtdPath(User $user): ?string
    {
        return $user->profile?->ttd_path;
    }
}
