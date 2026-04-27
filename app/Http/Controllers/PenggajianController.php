<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\User;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    // tampilkan semua data penggajian
    public function index(Request $request)
    {
        $periode = $request->periode ?? date('Y-m');
        
        $penggajianList = Penggajian::with('user')
            ->where('periode', $periode)
            ->get();

        return view('HR.penggajian.index', compact('penggajianList', 'periode'));
    }

    // buat slip gaji otomatis untuk semua karyawan aktif
    public function generate(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m',
        ], [
            'periode.required'     => 'periode harus dipilih',
            'periode.date_format'  => 'format periode tidak valid, gunakan format YYYY-MM',
        ]);

        try {
            // ambil semua karyawan yang punya gaji pokok
            $karyawanList = User::where('status', 'aktif')
                ->where('gaji_pokok', '>', 0)
                ->get();

            if ($karyawanList->isEmpty()) {
                flash()->error('tidak ada karyawan aktif yang memiliki data gaji');
                return redirect()->back();
            }

            $berhasil = 0;
            $sudahAda = 0;

            foreach ($karyawanList as $karyawan) {
                // cek apakah penggajian periode ini sudah dibuat untuk karyawan ini
                $cek = Penggajian::where('user_id', $karyawan->id)
                    ->where('periode', $request->periode)
                    ->first();

                if ($cek) {
                    // lewati karyawan yang sudah ada datanya
                    $sudahAda++;
                    continue;
                }

                // hitung gaji bersih (saat ini sama dengan gaji pokok, bisa ditambah tunjangan nanti)
                $gajiBersih = $karyawan->gaji_pokok;

                Penggajian::create([
                    'user_id'         => $karyawan->id,
                    'periode'         => $request->periode,
                    'gaji_pokok'      => $karyawan->gaji_pokok,
                    'total_tunjangan' => 0,
                    'total_potongan'  => 0,
                    'gaji_bersih'     => $gajiBersih,
                    'status'          => 'draft',
                ]);

                $berhasil++;
            }

            $pesan = "berhasil membuat {$berhasil} slip gaji";
            if ($sudahAda > 0) {
                $pesan .= ", {$sudahAda} karyawan dilewati karena sudah ada datanya";
            }

            flash()->success($pesan);
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('gagal generate data penggajian');
            return redirect()->back();
        }
    }

    // tampilkan detail slip gaji satu karyawan
    public function show($id)
    {
        // ambil data penggajian beserta data karyawannya
        $penggajian = Penggajian::with('user')->findOrFail($id);
        return view('HR.penggajian.show', compact('penggajian'));
    }

    // update komponen tunjangan dan potongan (hanya di status draft)
    public function updateKomponen(Request $request)
    {
        try {
            // validasi input
            $validated = $request->validate([
                'penggajian_id'   => 'required|exists:penggajians,id',
                'total_tunjangan' => 'nullable|numeric|min:0',
                'total_potongan'  => 'nullable|numeric|min:0',
                'catatan'         => 'nullable|string|max:500',
            ], [
                'penggajian_id.required'     => 'data penggajian tidak ditemukan',
                'penggajian_id.exists'       => 'slip gaji tidak valid',
                'total_tunjangan.numeric'    => 'tunjangan harus berupa angka',
                'total_tunjangan.min'        => 'tunjangan tidak boleh negatif',
                'total_potongan.numeric'     => 'potongan harus berupa angka',
                'total_potongan.min'         => 'potongan tidak boleh negatif',
                'catatan.max'                => 'catatan maksimal 500 karakter',
            ]);

            // ambil data penggajian
            $penggajian = Penggajian::findOrFail($validated['penggajian_id']);

            // cek status harus draft
            if ($penggajian->status !== 'draft') {
                flash()->error('slip gaji hanya bisa diubah jika status masih draft');
                return redirect()->back();
            }

            // siapkan data untuk diupdate
            $totalTunjangan = $validated['total_tunjangan'] ?? 0;
            $totalPotongan = $validated['total_potongan'] ?? 0;

            // hitung ulang gaji bersih
            $gajiBersih = $penggajian->gaji_pokok + $totalTunjangan - $totalPotongan;

            // update data
            $penggajian->update([
                'total_tunjangan' => $totalTunjangan,
                'total_potongan'  => $totalPotongan,
                'gaji_bersih'     => $gajiBersih,
                'catatan'         => $validated['catatan'] ?? null,
            ]);

            flash()->success('komponen gaji berhasil diupdate, gaji bersih: Rp ' . number_format($gajiBersih, 0, ',', '.'));
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error('Error in updateKomponen: ' . $e->getMessage());
            flash()->error('gagal mengupdate komponen gaji');
            return redirect()->back();
        }
    }

    // ubah status slip gaji menjadi dibayar
    public function bayar(Request $request)
    {
        try {
            $penggajian = Penggajian::findOrFail($request->id);
            $penggajian->update(['status' => 'dibayar']);

            flash()->success('status slip gaji berhasil diubah menjadi dibayar');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('gagal mengubah status slip gaji');
            return redirect()->back();
        }
    }
}
