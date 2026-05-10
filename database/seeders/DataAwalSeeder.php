<?php

namespace Database\Seeders;

use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataAwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 3 role untuk spatie permission: staff, supervisor, hr
        DB::table('roles')->insert([
            [
                'name' => 'hr',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'supervisor',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'staff',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ambil role ids
        $hrRole = DB::table('roles')->where('name', 'hr')->first();
        $supervisorRole = DB::table('roles')->where('name', 'supervisor')->first();
        $staffRole = DB::table('roles')->where('name', 'staff')->first();

        // buat user hr demo
        $hrUser = DB::table('users')->insertGetId([
            'user_id' => 'HR-0001',
            'name' => 'Admin Utama',
            'email' => 'admin@company.com',
            'password' => Hash::make('password'),
            'status' => 'aktif',
            'role_name' => 'hr',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // assign role hr ke user hr
        DB::table('model_has_roles')->insert([
            'role_id' => $hrRole->id,
            'model_type' => 'App\\Models\\User',
            'model_id' => $hrUser,
        ]);

        // buat user supervisor demo
        $supervisorUser = DB::table('users')->insertGetId([
            'user_id' => 'SUP-0001',
            'name' => 'Supervisor HR',
            'email' => 'supervisor@company.com',
            'password' => Hash::make('password'),
            'status' => 'aktif',
            'role_name' => 'supervisor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // assign role supervisor ke user supervisor
        DB::table('model_has_roles')->insert([
            'role_id' => $supervisorRole->id,
            'model_type' => 'App\\Models\\User',
            'model_id' => $supervisorUser,
        ]);

        // buat user staff demo
        $staffUser = DB::table('users')->insertGetId([
            'user_id' => 'STF-0001',
            'name' => 'Staff Karyawan',
            'email' => 'staff@company.com',
            'password' => Hash::make('password'),
            'status' => 'aktif',
            'role_name' => 'staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // assign role staff ke user staff
        DB::table('model_has_roles')->insert([
            'role_id' => $staffRole->id,
            'model_type' => 'App\\Models\\User',
            'model_id' => $staffUser,
        ]);

        // buat data shift awal
        DB::table('shifts')->insert([
            [
                'nama_shift' => 'shift pagi',
                'jam_masuk' => '08:00:00',
                'jam_keluar' => '17:00:00',
                'toleransi_menit' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_shift' => 'shift siang',
                'jam_masuk' => '14:00:00',
                'jam_keluar' => '22:00:00',
                'toleransi_menit' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_shift' => 'shift malam',
                'jam_masuk' => '22:00:00',
                'jam_keluar' => '06:00:00',
                'toleransi_menit' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
