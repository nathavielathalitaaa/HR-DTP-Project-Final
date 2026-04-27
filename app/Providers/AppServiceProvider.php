<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Surat;
use App\Policies\SuratPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policy untuk Surat
        \Illuminate\Support\Facades\Gate::policy(Surat::class, SuratPolicy::class);

        // Share notification data ke layout master
        \Illuminate\Support\Facades\View::composer('layouts.master', function ($view) {
            if (!auth()->check()) {
                $view->with([
                    'notifCuti' => collect(),
                    'notifSurat' => collect(),
                    'totalNotif' => 0,
                ]);
                return;
            }

            $user = auth()->user();

            // Ambil notifikasi cuti yang masih pending
            $notifCuti = \App\Models\Leave::where('status', 'Pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Ambil notifikasi surat berdasarkan role user
            $notifSurat = collect();
            if ($user->hasRole('supervisor')) {
                $notifSurat = Surat::where('status', 'submitted')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } elseif ($user->hasRole('hr')) {
                $notifSurat = Surat::where('status', 'approved_supervisor')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }

            // Hitung total notifikasi
            $totalCuti = \App\Models\Leave::where('status', 'Pending')->count();
            $totalSurat = $notifSurat->count();
            $totalNotif = $totalCuti + $totalSurat;

            $view->with([
                'notifCuti' => $notifCuti,
                'notifSurat' => $notifSurat,
                'totalNotif' => $totalNotif,
            ]);
        });
    }
}
