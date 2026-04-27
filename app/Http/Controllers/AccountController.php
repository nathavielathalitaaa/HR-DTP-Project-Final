<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeProfile;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    /** page account profile - read only (old) */
    

    public function profileDetail($user_id)
{
    $user    = User::where('user_id', $user_id)->first();
    $profile = $user?->profile()->firstOrCreate(['user_id' => $user->id ?? 0]);
    return view('pages.account-profile', compact('user', 'profile'));
}

    /** Show current user's own profile */
    public function showProfile()
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        
        return view('pages.account-profile', compact('user', 'profile'));
    }

    /** Update basic profile info */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ]);

        Auth::user()->update($validated);

        flash()->success('Profil berhasil diperbarui');
        return redirect()->route('profile.show');
    }

    /** Upload TTD image */
    public function uploadTtd(Request $request)
    {
        $request->validate([
            'ttd' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'ttd.required' => 'File tanda tangan harus diunggah',
            'ttd.image' => 'File harus berupa gambar',
            'ttd.mimes' => 'Format file harus PNG, JPG, atau JPEG',
            'ttd.max' => 'Ukuran file maksimal 2MB',
        ]);

        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        // Ensure directory exists
        Storage::makeDirectory('private/ttd');

        // Get file extension
        $file = $request->file('ttd');
        $ext = $file->getClientOriginalExtension();
        $filename = 'ttd/' . $user->id . '.' . $ext;

        // Delete old TTD file if exists
        if ($profile->ttd_path) {
            Storage::disk('local')->delete('private/' . $profile->ttd_path);
        }

        // Store new TTD file
        Storage::disk('local')->putFileAs('private', $file, $filename);
        $profile->update(['ttd_path' => $filename]);

        flash()->success('Tanda tangan berhasil diunggah');
        return redirect()->route('profile.show');
    }

    /** Set or change PIN */
    public function setPin(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        $request->validate([
            'pin' => 'required|digits:6',
            'pin_confirmation' => 'required|same:pin',
            'current_pin' => $profile->pin ? 'required' : 'nullable',
        ], [
            'pin.required' => 'PIN baru harus diisi',
            'pin.digits' => 'PIN harus terdiri dari 6 digit angka',
            'pin_confirmation.required' => 'Konfirmasi PIN harus diisi',
            'pin_confirmation.same' => 'Konfirmasi PIN tidak cocok',
            'current_pin.required' => 'PIN lama harus diisi',
        ]);

        // If user already has PIN, verify current PIN
        if ($profile->pin) {
            if (!$profile->checkPin($request->current_pin)) {
                return back()->withErrors(['current_pin' => 'PIN lama tidak sesuai']);
            }
        }

        // Set new PIN
        $profile->setPin($request->pin);

        flash()->success('PIN approval berhasil diatur');
        return redirect()->route('profile.show');
    }

    /** Serve TTD image securely */
    public function showTtd()
    {
        $profile = Auth::user()->profile;

        if (!$profile || !$profile->ttd_path) {
            abort(404);
        }

        $path = storage_path('app/private/' . $profile->ttd_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /** Show onboarding page */
    public function showOnboarding()
    {
        $user    = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        $step    = !$profile->ttd_path ? 'ttd' : (!$profile->pin ? 'pin' : 'done');

        if ($step === 'done') return redirect()->route('home');

        return view('pages.onboarding', compact('user', 'profile', 'step'));
    }

    /** Upload TTD during onboarding */
    public function onboardingTtd(Request $request)
    {
        $request->validate([
            'ttd' => 'required|image|mimes:png|max:2048',
        ], [
            'ttd.required' => 'Tanda tangan wajib diunggah',
            'ttd.mimes'    => 'File harus berformat PNG',
            'ttd.max'      => 'Ukuran file maksimal 2MB',
        ]);

        $user    = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        $ext      = 'png';
        $filename = $user->id . '.' . $ext;

        Storage::makeDirectory('private/ttd');

        if ($profile->ttd_path) {
            Storage::delete('private/' . $profile->ttd_path);
        }

        $request->file('ttd')->storeAs('private/ttd', $filename);
        $profile->update(['ttd_path' => 'ttd/' . $filename]);

        flash()->success('Tanda tangan berhasil disimpan. Sekarang buat PIN Anda.');
        return redirect()->route('onboarding');
    }

    /** Set PIN during onboarding */
    public function onboardingPin(Request $request)
    {
        $request->validate([
            'pin'              => 'required|digits:6',
            'pin_confirmation' => 'required|same:pin',
        ], [
            'pin.required'              => 'PIN wajib diisi',
            'pin.digits'                => 'PIN harus 6 digit angka',
            'pin_confirmation.required' => 'Konfirmasi PIN wajib diisi',
            'pin_confirmation.same'     => 'Konfirmasi PIN tidak cocok',
        ]);

        $user    = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        $profile->setPin($request->pin);

        flash()->success('PIN berhasil dibuat. Selamat datang di sistem HRIS Sinergi!');
        return redirect()->route('home');
    }
}
