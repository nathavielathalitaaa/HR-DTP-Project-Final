<?php

namespace Database\Seeders;

use App\Models\SuratType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuratTypeSeeder extends Seeder
{
    public function run()
    {
        $adminId = User::where('role_name', 'super-admin')->first()?->id ?? 1;

        $types = [
            [
                'nama' => 'Surat Izin',
                'kode' => 'izin',
                'deskripsi' => 'Surat izin tidak masuk kerja atau izin terlambat.',
                'nomor_format' => [
                    ['type' => 'NOMOR_URUT'],
                    ['type' => 'KODE_SURAT'],
                    ['type' => 'LEMBAGA', 'value' => 'HRD'],
                    ['type' => 'BULAN_ROMAWI'],
                    ['type' => 'TAHUN'],
                ],
                'approvers' => [
                    ['urutan' => 1, 'jabatan_label' => 'HOD', 'label' => 'Requested by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 2, 'jabatan_label' => 'Purchasing', 'label' => 'Checked by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 3, 'jabatan_label' => 'Owner Rep', 'label' => 'Checked by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 4, 'jabatan_label' => 'Direktur', 'label' => 'Approved by', 'metode_ttd' => 'stamp'],
                ]
            ],
            [
                'nama' => 'Surat Resign',
                'kode' => 'resign',
                'deskripsi' => 'Surat pengunduran diri karyawan.',
                'nomor_format' => [
                    ['type' => 'KODE_SURAT'],
                    ['type' => 'NOMOR_URUT'],
                    ['type' => 'TAHUN'],
                ],
                'approvers' => [
                    ['urutan' => 1, 'jabatan_label' => 'HOD', 'label' => 'Acknowledged by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 2, 'jabatan_label' => 'Owner Rep', 'label' => 'Reviewed by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 3, 'jabatan_label' => 'Direktur', 'label' => 'Approved by', 'metode_ttd' => 'stamp'],
                ]
            ],
            [
                'nama' => 'Purchase Requisition',
                'kode' => 'PR',
                'deskripsi' => 'Permohonan pembelian barang atau jasa.',
                'nomor_format' => [
                    ['type' => 'NOMOR_URUT'],
                    ['type' => 'LEMBAGA', 'value' => 'HRD-PR'],
                    ['type' => 'BULAN_ROMAWI'],
                    ['type' => 'TAHUN'],
                ],
                'approvers' => [
                    ['urutan' => 1, 'jabatan_label' => 'HOD', 'label' => 'Requested by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 2, 'jabatan_label' => 'Purchasing', 'label' => 'Checked by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 3, 'jabatan_label' => 'Owner Rep', 'label' => 'Verified by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 4, 'jabatan_label' => 'Direktur', 'label' => 'Approved by', 'metode_ttd' => 'stamp'],
                ]
            ],
            [
                'nama' => 'Surat Permohonan Cuti',
                'kode' => 'cuti',
                'deskripsi' => 'Permohonan cuti tahunan atau cuti lainnya.',
                'nomor_format' => [
                    ['type' => 'NOMOR_URUT'],
                    ['type' => 'KODE_SURAT'],
                    ['type' => 'LEMBAGA', 'value' => 'HRD'],
                    ['type' => 'BULAN_ROMAWI'],
                    ['type' => 'TAHUN'],
                ],
                'approvers' => [
                    ['urutan' => 1, 'jabatan_label' => 'HOD', 'label' => 'Requested by', 'metode_ttd' => 'stamp'],
                    ['urutan' => 2, 'jabatan_label' => 'Owner Rep', 'label' => 'Approved by', 'metode_ttd' => 'stamp'],
                ]
            ]
        ];

        DB::transaction(function () use ($types, $adminId) {
            foreach ($types as $t) {
                $suratType = SuratType::updateOrCreate(
                    ['kode' => $t['kode']],
                    [
                        'nama' => $t['nama'],
                        'deskripsi' => $t['deskripsi'],
                        'nomor_format' => $t['nomor_format'],
                        'created_by' => $adminId,
                        'nomor_reset' => 'yearly',
                        'is_active' => true,
                    ]
                );

                $suratType->approvers()->delete();
                foreach ($t['approvers'] as $app) {
                    $suratType->approvers()->create($app);
                }
            }
        });
    }
}
