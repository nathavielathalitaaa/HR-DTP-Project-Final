<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use App\Models\User; 
use App\Models\EmployeeProfile; 
use Session; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Hash; 
use App\Models\ActivityLog; 

class AccountController extends Controller // class controller utk fitur manajemen akun & profil user
{
    
    // fungsi ini nampilin detail profil user berdasarkan user_id (bisa buat hr liat profil karyawan lain)
    public function profileDetail($user_id)
    {
        $user    = User::where('user_id', $user_id)->first();
        $profile = $user?->profile()->firstOrCreate(['user_id' => $user->id ?? 0]);
        return view('pages.account-profile', compact('user', 'profile'));
    }

    // fungsi ini nampilin profil user yg lagi login aja, otomatis ambil data dr session auth
    public function showProfile()
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        
        return view('pages.account-profile', compact('user', 'profile'));
    }

    // fungsi ini update info dasar profil (nama, no hp, lokasi), cuma bs diupdate oleh hr atau user itu sendiri
    public function updateProfile(Request $request, $id = null)
    {
        $id = $id ?? Auth::id();
        $user = User::findOrFail($id);

        // cek security: klo yg login bukan user tsb & bukan hr, blokir akses
        if (Auth::id() != $id && !Auth::user()->hasRole('hr')) {
            abort(403, 'Unauthorized action.');
        }

        // validasi input: nama wajib, no hp & lokasi optional dgn batas panjang tertentu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        flash()->success('Profil berhasil diperbarui');
        return redirect()->back(); // redirect balik biar konteks halaman tetep (profil sendiri / profil karyawan)
    }
    
    // fungsi ini handle upload & update foto profil user, pake validasi image & simpan ke public folder
    public function updatePhoto(Request $request)
    {
        // validasi: file wajib, harus image, format jpeg/png/jpg/gif, max 2mb
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        
        // if ini proses upload klo file beneran ada: generate nama unik, simpan ke default storage, hapus foto lama klo ada, update db
        if ($request->hasFile('photo')) {
            if ($user->avatar && $user->avatar !== 'profile.png') {
                Storage::disk(config('filesystems.default'))->delete($user->avatar);
                if (file_exists(public_path('assets/images/user/' . $user->avatar))) {
                    @unlink(public_path('assets/images/user/' . $user->avatar));
                }
            }
            
            $path = $request->file('photo')->store('avatars');
            $user->update(['avatar' => $path]);
            
            return response()->json([
                'success' => true,
                'url' => $user->avatar_url
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Gagal upload foto'], 400);
    }

    // fungsi ini hapus foto profil user dan reset ke initial
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk(config('filesystems.default'))->delete($user->avatar);
            if (file_exists(public_path('assets/images/user/' . $user->avatar))) {
                @unlink(public_path('assets/images/user/' . $user->avatar));
            }
        }

        $user->update(['avatar' => null]);

        return response()->json(['success' => true]);
    }

    // fungsi ini handle upload ttd dgn validasi: wajib png, transparan, ukuran & resolusi spesifik, simpan ke private storage
    public function uploadTtd(Request $request)
    {
        // validasi detail: wajib png krn support transparansi, max 2mb, resolusi landscape 300x100 s/d 1000x400 px
        $request->validate([
            'ttd' => 'required|image|mimes:png|max:2048|dimensions:min_width=300,min_height=100,max_width=1000,max_height=400',
        ], [
            'ttd.required'   => 'File tanda tangan harus diunggah',
            'ttd.image'      => 'File harus berupa gambar',
            'ttd.mimes'      => 'Format file harus PNG (transparan). JPG/JPEG tidak didukung karena tidak support transparansi.',
            'ttd.max'        => 'Ukuran file maksimal 2MB',
            'ttd.dimensions' => 'Resolusi gambar harus minimal 300×100 px dan maksimal 1000×400 px (landscape).',
        ]);

        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        if ($profile->ttd_path) {
            Storage::disk(config('filesystems.default'))->delete($profile->ttd_path);
            Storage::disk('local')->delete('private/' . $profile->ttd_path);
        }

        $path = $request->file('ttd')->storeAs('ttd', $user->id . '.png');
        $profile->update(['ttd_path' => $path]);

        flash()->success('Tanda tangan berhasil diunggah');
        return redirect()->route('profile.show');
    }

    // fungsi ini handle upload digital signature ke default storage, bs buat hr update signature karyawan lain
    public function uploadSignature(Request $request, $id = null)
    {
        $id = $id ?? Auth::id();
        $user = User::findOrFail($id);

        // cek authorization: cuma user sendiri atau hr yg bs update signature
        if (Auth::id() != $id && !Auth::user()->hasRole('hr')) {
            abort(403, 'Unauthorized action.');
        }

        // validasi: file signature wajib image, format jpg/jpeg/png, max 2mb
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'signature.required' => 'File tanda tangan wajib diunggah',
            'signature.image' => 'File harus berupa gambar',
            'signature.mimes' => 'Format file yang didukung: JPG, JPEG, PNG',
            'signature.max' => 'Ukuran maksimal file 2MB',
        ]);

        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        // if ini proses upload: hapus signature lama klo ada, simpen yg baru ke folder 'signatures' di default disk
        if ($request->hasFile('signature')) {
            // delete old signature if exists
            if ($profile->signature_path) {
                Storage::disk(config('filesystems.default'))->delete($profile->signature_path);
                Storage::disk('local')->delete('private/' . $profile->signature_path);
            }

            // save new signature to default storage
            $path = $request->file('signature')->store('signatures');
            $profile->update(['signature_path' => $path]);
        }

        flash()->success('Tanda tangan digital berhasil disimpan');
        return redirect()->back();
    }

    // fungsi ini hapus digital signature user dari default storage, cuma bs dilakukan oleh user sendiri atau hr
    public function deleteSignature($id = null)
    {
        $id = $id ?? Auth::id();
        $user = User::findOrFail($id);

        // cek authorization: blokir klo bukan user tsb & bukan hr
        if (Auth::id() != $id && !Auth::user()->hasRole('hr')) {
            abort(403, 'Unauthorized action.');
        }

        $profile = $user->profile;

        // if ini cek klo profile & signature_path ada, baru hapus file dr storage & update db jadi null
        if ($profile && $profile->signature_path) {
            Storage::disk(config('filesystems.default'))->delete($profile->signature_path);
            Storage::disk('local')->delete('private/' . $profile->signature_path);
            $profile->update(['signature_path' => null]);
            flash()->success('Tanda tangan digital berhasil dihapus');
        } else {
            flash()->error('Tanda tangan tidak ditemukan');
        }

        return redirect()->back();
    }

    // fungsi ini handle set/change pin approval user, dgn validasi 6 digit angka & verifikasi pin lama klo udah pernah set
    public function setPin(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        // validasi: pin baru wajib 6 digit, konfirmasi wajib sama, current_pin wajib klo user udah punya pin sebelumnya
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

        // if ini verifikasi pin lama klo user udah pernah set pin sebelumnya, klo salah return error
        if ($profile->pin) {
            if (!$profile->checkPin($request->current_pin)) {
                return back()->withErrors(['current_pin' => 'PIN lama tidak sesuai']);
            }
        }

        // set new pin
        $profile->setPin($request->pin);

        ActivityLog::log('update_pin', null, auth()->user()->name . " memperbarui PIN approval");

        flash()->success('PIN approval berhasil diatur');
        return redirect()->route('profile.show');
    }

    // fungsi ini serve file ttd user secara secure via storage facade, cuma bs diakses klo file beneran ada & user authorized
    public function showTtd()
    {
        $profile = Auth::user()->profile;

        if (!$profile) abort(404);

        $disk = Storage::disk(config('filesystems.default'));
        $targetPath = $profile->signature_path ?? $profile->ttd_path;

        if ($targetPath) {
            if ($disk->exists($targetPath)) {
                return $disk->response($targetPath);
            }
            if ($disk->exists('private/' . $targetPath)) {
                return $disk->response('private/' . $targetPath);
            }

            $localPossiblePaths = [
                storage_path('app/public/' . $targetPath),
                storage_path('app/private/' . $targetPath),
                storage_path('app/private/private/' . $targetPath),
                storage_path('app/' . $targetPath),
            ];
            foreach ($localPossiblePaths as $p) {
                if (file_exists($p)) {
                    $mime = str_ends_with($p, '.png') ? 'image/png' : 'image/jpeg';
                    return response()->file($p, ['Content-Type' => $mime]);
                }
            }
        }

        abort(404, 'Signature file not found');
    }

    // fungsi ini update email user, wajib verifikasi password dulu buat keamanan
    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        // validasi: email wajib, format email valid, unique kecuali buat user ini sendiri, password wajib utk verifikasi
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required',
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh akun lain',
            'password.required' => 'Password diperlukan untuk verifikasi identitas',
        ]);

        // if ini cek klo password yg diinput gk cocok dgn hash di db, return error
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password yang Anda masukkan salah']);
        }

        $user->update(['email' => $request->email]);

        flash()->success('Email berhasil diperbarui');
        return redirect()->route('profile.show');
    }

    // fungsi ini update password user, wajib isi password lama & konfirmasi password baru
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // validasi: current_password wajib, new_password wajib min 8 karakter & confirmed
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);

        // if ini verifikasi password lama, klo gk cocok return error
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->update([
            'password'             => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        ActivityLog::log('update_password', null, auth()->user()->name . " memperbarui password akun");

        flash()->success('Password berhasil diperbarui');
        return redirect()->route('profile.show');
    }

    // fungsi ini nampilin halaman onboarding, nentuin step berikutnya based on kelengkapan ttd & pin user
    public function showOnboarding()
    {
        $user    = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        // logic ini nentuin step: klo blm ada ttd -> step 'ttd', klo udah ttd tp blm pin -> step 'pin', klo udah semua -> 'done'
        $step    = !$profile->ttd_path ? 'ttd' : (!$profile->pin ? 'pin' : 'done');

        // if ini redirect ke home klo user udah selesai onboarding (step 'done')
        if ($step === 'done') return redirect()->route('home');

        return view('pages.onboarding', compact('user', 'profile', 'step'));
    }

    // fungsi ini handle upload ttd khusus selama proses onboarding, validasi lebih simpel & langsung redirect ke step pin
    public function onboardingTtd(Request $request)
    {
        // validasi: ttd wajib, format png, max 2mb
        $request->validate([
            'ttd' => 'required|image|mimes:png|max:2048',
        ], [
            'ttd.required' => 'Tanda tangan wajib diunggah',
            'ttd.mimes'    => 'File harus berformat PNG',
            'ttd.max'      => 'Ukuran file maksimal 2MB',
        ]);

        $user    = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        $filename = $user->id . '.png';

        if ($profile->ttd_path) {
            Storage::disk(config('filesystems.default'))->delete($profile->ttd_path);
            Storage::disk('local')->delete('private/' . $profile->ttd_path);
        }

        $path = $request->file('ttd')->storeAs('ttd', $filename);
        $profile->update(['ttd_path' => $path]);

        flash()->success('Tanda tangan berhasil disimpan. Sekarang buat PIN Anda.');
        return redirect()->route('onboarding');
    }

    // fungsi ini handle set pin khusus selama onboarding, gk perlu verifikasi pin lama krn ini pertama kali set
    public function onboardingPin(Request $request)
    {
        // validasi: pin wajib 6 digit angka, konfirmasi wajib sama
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