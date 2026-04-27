<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');

        $query = Absensi::select(['id', 'user_id', 'tanggal', 'jam_masuk', 'jam_keluar', 'status', 'keterangan'])
            ->with(['user' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->where('tanggal', 'like', $bulan . '%');

        // jika user bukan hr, tampilkan hanya absensi miliknya sendiri
        if (!auth()->user()->hasRole('hr')) {
            $query->where('user_id', auth()->user()->id);
        }

        $absensiList = $query->orderBy('tanggal', 'desc')->get();

        $absensiHariIni = Absensi::where('user_id', auth()->user()->id)
            ->where('tanggal', date('Y-m-d'))
            ->first();

        return view('HR.absensi.index', compact('absensiList', 'bulan', 'absensiHariIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'tanggal'   => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'status'    => 'required|in:hadir,izin,sakit,alpha,cuti',
        ], [
            'user_id.required'   => 'karyawan harus dipilih',
            'tanggal.required'   => 'tanggal harus diisi',
            'status.required'    => 'status kehadiran harus dipilih',
            'status.in'          => 'status tidak valid',
        ]);

        try {
            $sudahAda = Absensi::where('user_id', $request->user_id)
                ->where('tanggal', $request->tanggal)
                ->first();

            if ($sudahAda) {
                flash()->error('absensi untuk karyawan ini pada tanggal tersebut sudah ada');
                return redirect()->back();
            }

            Absensi::create([
                'user_id'    => $request->user_id,
                'tanggal'    => $request->tanggal,
                'jam_masuk'  => $request->jam_masuk,
                'jam_keluar' => $request->jam_keluar,
                'status'     => $request->status,
                'keterangan' => $request->keterangan,
            ]);

            flash()->success('data absensi berhasil disimpan');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('gagal menyimpan data absensi');
            return redirect()->back();
        }
    }

    public function clockIn(Request $request)
    {
        try {
            $userId   = auth()->id();
            $hari_ini = date('Y-m-d');
            $jam_ini  = date('H:i:s');

            $sudahAbsen = Absensi::where('user_id', $userId)
                ->where('tanggal', $hari_ini)
                ->first();

            if ($sudahAbsen) {
                flash()->error('kamu sudah melakukan absen masuk hari ini');
                return redirect()->back();
            }

            Absensi::create([
                'user_id'   => $userId,
                'tanggal'   => $hari_ini,
                'jam_masuk' => $jam_ini,
                'status'    => 'hadir',
            ]);

            flash()->success('berhasil absen masuk pukul ' . $jam_ini);
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('gagal melakukan absen masuk');
            return redirect()->back();
        }
    }

    public function clockOut(Request $request)
    {
        try {
            $userId   = auth()->id();
            $hari_ini = date('Y-m-d');
            $jam_ini  = date('H:i:s');

            $absensi = Absensi::where('user_id', $userId)
                ->where('tanggal', $hari_ini)
                ->first();

            if (!$absensi) {
                flash()->error('kamu belum melakukan absen masuk hari ini');
                return redirect()->back();
            }

            if ($absensi->jam_keluar) {
                flash()->error('kamu sudah melakukan absen keluar hari ini');
                return redirect()->back();
            }

            $absensi->update(['jam_keluar' => $jam_ini]);

            flash()->success('berhasil absen keluar pukul ' . $jam_ini);
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('gagal melakukan absen keluar');
            return redirect()->back();
        }
    }
}