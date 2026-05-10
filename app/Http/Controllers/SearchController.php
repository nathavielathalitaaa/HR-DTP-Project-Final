<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // fungsi untuk mencari karyawan dan departemen berdasarkan kata kunci
    public function cari(Request $request)
    {
        // ambil kata kunci dari query string
        $kata = $request->q;

        // kalau tidak ada kata kunci, kembalikan array kosong
        if (empty($kata) || strlen($kata) < 2) {
            return response()->json([]);
        }

        // cari karyawan yang namanya, emailnya, atau posisinya mengandung kata kunci
        $karyawan = User::where('name', 'like', '%' . $kata . '%')
            ->orWhere('email', 'like', '%' . $kata . '%')
            ->orWhere('position', 'like', '%' . $kata . '%')
            ->orWhere('user_id', 'like', '%' . $kata . '%')
            ->select('id', 'name', 'email', 'user_id', 'position', 'avatar', 'department')
            ->limit(6)
            ->get();



        // format hasil untuk dikirim ke frontend
        $hasil = [];

        foreach ($karyawan as $k) {
            $hasil[] = [
                'tipe'   => 'karyawan',
                'label'  => $k->name,
                'sub'    => $k->position ?? 'karyawan',
                'id'     => $k->user_id,
                'url'    => url('page/account/' . $k->user_id),
                'avatar' => $k->avatar ? asset('assets/images/user/' . $k->avatar) : null,
            ];
        }



        return response()->json($hasil);
    }
}

