<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\EmployeeProfile;
use App\Models\SuratType;
use Spatie\Permission\PermissionRegistrar;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Starting production setup...');

        // ── STEP 1: Wipe all transactional & test data ───────────────
        Schema::disableForeignKeyConstraints();

        $this->command->info('Clearing transactional data...');
        DB::table('document_approvals')->truncate();
        DB::table('surat_type_approvers')->truncate();
        DB::table('surats')->truncate();
        DB::table('surat_types')->truncate();
        DB::table('absensis')->truncate();
        DB::table('notifications')->truncate();
        DB::table('activity_logs')->truncate();
        DB::table('sessions')->truncate();
        DB::table('position_types')->truncate();
        DB::table('user_types')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('employee_profiles')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        // ── STEP 2: Ensure roles exist ────────────────────────────────
        $this->command->info('Setting up roles...');
        $roles = ['hr', 'supervisor', 'staff', 'head_of_department'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── STEP 3: Create the one admin HR account ───────────────────
        $this->command->info('Creating admin HR account...');
        $admin = User::firstOrCreate(
            ['email' => 'admin@sinergihotel.com'],
            [
                'name'      => 'Admin HR',
                'password'  => bcrypt('Sinergi@2026'),
                'user_id'   => 'SIN-0001',
                'status'    => 'aktif',
                'role_name' => 'hr',
            ]
        );
        $admin->update([
            'password'  => bcrypt('Sinergi@2026'),
            'status'    => 'aktif',
            'role_name' => 'hr',
        ]);
        $admin->assignRole('hr');

        EmployeeProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'jabatan'           => 'Human Resources',
                'status_pernikahan' => 'belum_menikah',
            ]
        );

        // ── STEP 4: Default surat types ───────────────────────────────
        $this->command->info('Setting up surat types...');
        $this->call(SuratTypeSeeder::class);

        // ── STEP 5: Position types ────────────────────────────────────
        $this->command->info('Seeding position types...');
        DB::table('position_types')->insert([
            ['position' => 'Front Office'],
            ['position' => 'Housekeeping'],
            ['position' => 'Food & Beverage'],
            ['position' => 'Engineering & Maintenance'],
            ['position' => 'Security'],
            ['position' => 'Accounting & Finance'],
            ['position' => 'Human Resources'],
            ['position' => 'Marketing & Sales'],
            ['position' => 'Purchasing'],
            ['position' => 'General Manager'],
            ['position' => 'Supervisor'],
            ['position' => 'Staff Umum'],
        ]);

        // ── STEP 6: User (employment) types ───────────────────────────
        DB::table('user_types')->insert([
            ['type_name' => 'Active'],
            ['type_name' => 'Inactive'],
            ['type_name' => 'Probation'],
            ['type_name' => 'Contract'],
            ['type_name' => 'Permanent'],
        ]);

        // ── STEP 7: Wipe all physical PDF / storage files ─────────────
        $this->command->info('Clearing physical storage files...');
        $storageDirs = [
            storage_path('app/public/surat'),
            storage_path('app/public/final-pdf'),
            storage_path('app/private/ttd'),
            storage_path('app/private/signatures'),
        ];
        foreach ($storageDirs as $dir) {
            if (is_dir($dir)) {
                $files = array_merge(
                    glob($dir . '/*.pdf') ?: [],
                    glob($dir . '/*.png') ?: [],
                    glob($dir . '/*.jpg') ?: []
                );
                foreach ($files as $file) {
                    if (is_file($file)) @unlink($file);
                }
            }
        }
        // Also clear covers subfolder
        $coversDir = storage_path('app/public/surat/covers');
        if (is_dir($coversDir)) {
            foreach (glob($coversDir . '/*') ?: [] as $file) {
                if (is_file($file)) @unlink($file);
            }
        }

        // ── STEP 8: Clear all caches ──────────────────────────────────
        $this->command->info('Clearing caches...');
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Summary ───────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('=== Production Setup Complete! ===');
        $this->command->info('Admin Email : admin@sinergihotel.com');
        $this->command->info('Password    : Sinergi@2026');
        $this->command->warn('IMPORTANT: Change the admin password after first login!');
    }
}
