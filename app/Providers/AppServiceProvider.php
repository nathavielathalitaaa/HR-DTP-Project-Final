<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Surat;
use App\Policies\SuratPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Gate::policy(Surat::class, SuratPolicy::class);

        \Illuminate\Support\Facades\View::composer('layouts.master', function ($view) {
            if (!auth()->check()) {
                $view->with([
                    'notifSurat' => collect(),
                    'totalNotif' => 0,
                ]);
                return;
            }

            $user = auth()->user();

            // Notifikasi surat berdasarkan jabatan approver
            $notifSurat = collect();
            $jabatan = $user->profile?->jabatan;

            if ($jabatan) {
                $notifSurat = Surat::whereHas('approvals', function ($q) use ($jabatan) {
                    $q->where('jabatan', $jabatan)
                        ->where('status', 'waiting')
                        ->where('is_read', false);
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            }

            $view->with([
                'notifSurat' => $notifSurat,
                'totalNotif' => $notifSurat->count(),
            ]);
        });
    }
}