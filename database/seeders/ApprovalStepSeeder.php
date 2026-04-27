<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovalStep;

class ApprovalStepSeeder extends Seeder
{
    public function run(): void
    {
        ApprovalStep::truncate();

        $flows = [
            'surat_izin' => [
                ['step_order' => 1, 'jabatan' => 'hod', 'label' => 'Head of Department'],
            ],
            'surat_permohonan' => [
                ['step_order' => 1, 'jabatan' => 'hod', 'label' => 'Head of Department'],
            ],
            'surat_resign' => [
                ['step_order' => 1, 'jabatan' => 'hod',      'label' => 'Head of Department'],
                ['step_order' => 2, 'jabatan' => 'hr',        'label' => 'Human Resources'],
                ['step_order' => 3, 'jabatan' => 'direktur',  'label' => 'Direktur'],
            ],
            'surat_surat_tugas' => [
                ['step_order' => 1, 'jabatan' => 'hod',       'label' => 'Head of Department'],
                ['step_order' => 2, 'jabatan' => 'owner_rep', 'label' => 'Owner Representative'],
                ['step_order' => 3, 'jabatan' => 'direktur',  'label' => 'Direktur'],
            ],
            'surat_rekomendasi' => [
                ['step_order' => 1, 'jabatan' => 'hod',      'label' => 'Head of Department'],
                ['step_order' => 2, 'jabatan' => 'direktur', 'label' => 'Direktur'],
            ],
            'surat_lainnya' => [
                ['step_order' => 1, 'jabatan' => 'hod',       'label' => 'Head of Department'],
                ['step_order' => 2, 'jabatan' => 'purchasing', 'label' => 'Purchasing'],
                ['step_order' => 3, 'jabatan' => 'owner_rep',  'label' => 'Owner Representative'],
                ['step_order' => 4, 'jabatan' => 'direktur',   'label' => 'Direktur'],
            ],
        ];

        foreach ($flows as $documentType => $steps) {
            foreach ($steps as $step) {
                ApprovalStep::create([
                    'document_type' => $documentType,
                    'step_order'    => $step['step_order'],
                    'jabatan'       => $step['jabatan'],
                    'label'         => $step['label'],
                ]);
            }
        }

        $this->command->info('ApprovalStep seeder (B+) selesai.');
    }
}

