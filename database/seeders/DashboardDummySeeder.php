<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Penggajian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DashboardDummySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('membuat data dummy untuk dashboard hr & payroll...');

        // ==================== 1. BUAT SUPER ADMIN ====================
        User::updateOrCreate(
            ['email' => 'admin@hris.test'],
            [
                'user_id'      => 'KH-0001',
                'name'         => 'super admin',
                'role_name'    => 'super-admin',
                'status'       => 'aktif',
                'password'     => Hash::make('password'),
                'gaji_pokok'   => 0,
                'avatar'       => 'default-avatar.png',
                'join_date'    => now()->format('Y-m-d'),
                'phone_number' => '081234567890',
                'location'     => 'surabaya',
            ]
        );

        $this->command->info('admin@hris.test / password');

        $departemenNames = ['operasional', 'keuangan', 'sdm', 'pemasaran', 'it'];

        // ==================== 3. BUAT 99 KARYAWAN DUMMY ====================
        $this->command->info('membuat data karyawan dummy...');

        for ($i = 2; $i <= 100; $i++) {
            $namaLengkap = fake('id_ID')->name();
            $email = Str::slug($namaLengkap, '.') . '@sinergihotel.com';

            $user = User::updateOrCreate([
                'email' => $email
            ], [
                'user_id'      => 'KH-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name'         => $namaLengkap,
                'position'     => fake()->randomElement(['staff', 'supervisor', 'manager']),
                'department'   => fake()->randomElement($departemenNames),
                'role_name'    => 'karyawan',
                'status'       => 'aktif',
                'phone_number' => '08' . fake()->numerify('##########'),
                'location'     => 'surabaya',
                'join_date'    => fake()->dateTimeBetween('-4 years', 'now')->format('Y-m-d'),
                'gaji_pokok'   => fake()->numberBetween(2800000, 9500000),
                'password'     => Hash::make('password'),
                'avatar'       => 'default-avatar.png',
            ]);

            // buat absensi 45 hari terakhir + kemungkinan lembur
            for ($d = 0; $d < 45; $d++) {
                $tanggal = Carbon::now()->subDays($d);
                if ($tanggal->isWeekend()) continue;

                $jamMasuk = $tanggal->copy()->setTime(8, rand(0, 30));
                $jamKeluar = $jamMasuk->copy()->addHours(8)->addMinutes(rand(0, 180)); // bisa lembur

                Absensi::updateOrCreate([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal->format('Y-m-d')
                ], [
                    'jam_masuk'  => $jamMasuk->format('H:i:s'),
                    'jam_keluar' => $jamKeluar->format('H:i:s'),
                    'status'     => 'hadir',
                ]);
            }
        }

        // ==================== 4. BUAT PENGGAJIAN DENGAN LEMBUR ====================
        $periode = now()->format('Y-m');
        $users = User::where('status', 'aktif')->where('gaji_pokok', '>', 0)->take(80)->get();

        foreach ($users as $user) {
            $jamLembur = rand(5, 30);
            $gajiLembur = $jamLembur * 25000;

            Penggajian::updateOrCreate([
                'user_id' => $user->id,
                'periode' => $periode
            ], [
                'gaji_pokok'      => $user->gaji_pokok,
                'total_tunjangan' => rand(400000, 1500000) + $gajiLembur,
                'total_potongan'  => rand(150000, 600000),
                'gaji_bersih'     => $user->gaji_pokok + rand(400000, 1500000) + $gajiLembur - rand(150000, 600000),
                'status'          => 'dibayar',
            ]);
        }


        $this->command->info('seeder dashboard dummy selesai. total 100 karyawan + 1 super admin.');
    }
}