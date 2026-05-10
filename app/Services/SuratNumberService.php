<?php

namespace App\Services;

use App\Models\SuratType;
use Illuminate\Support\Facades\DB;

class SuratNumberService
{
    public function generate(SuratType $suratType): string
    {
        return DB::transaction(function () use ($suratType) {
            $suratType = SuratType::lockForUpdate()->find($suratType->id);
            $counter = $suratType->nomor_counter + 1;
            
            $now = now();
            $bulanRomawi = ['I','II','III','IV','V','VI',
                            'VII','VIII','IX','X','XI','XII'][$now->month - 1];
            
            $parts = [];
            foreach ($suratType->nomor_format as $komponen) {
                $parts[] = match($komponen['type']) {
                    'NOMOR_URUT'   => str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'KODE_SURAT'   => strtoupper($suratType->kode),
                    'LEMBAGA'      => $komponen['value'] ?? 'HRD',
                    'BULAN_ROMAWI' => $bulanRomawi,
                    'TAHUN'        => $now->year,
                    'CUSTOM'       => $komponen['value'] ?? '',
                    default        => '',
                };
            }
            
            $nomor = implode('/', array_filter($parts));
            $suratType->update(['nomor_counter' => $counter]);
            
            return $nomor;
        });
    }
}
