<?php

namespace App\Http\Controllers;

use App\Models\SuratType;
use App\Models\SuratTypeApprover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuratTypeController extends Controller
{
    public function index()
    {
        $suratTypes = SuratType::withCount(['surats', 'approvers'])->get();
        return view('surat-type.index', compact('suratTypes'));
    }

    public function create()
    {
        $users = \App\Models\User::role(['hr', 'supervisor'])->get();
        return view('surat-type.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:surat_types,kode',
            'nomor_format' => 'required|array',
            'approvers' => 'required|array|min:1',
            'approvers.*.jabatan_label' => 'required|string|max:100',
            'approvers.*.label' => 'required|string|max:100',
            'approvers.*.user_id' => 'nullable|exists:users,id',
            'approvers.*.metode_ttd' => 'required|in:stamp,append',
        ]);

        DB::transaction(function () use ($request) {
            $suratType = SuratType::create([
                'nama' => $request->nama,
                'kode' => Str::slug($request->kode),
                'deskripsi' => $request->deskripsi,
                'nomor_format' => $request->nomor_format,
                'nomor_reset' => $request->nomor_reset ?? 'yearly',
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
            ]);

            foreach ($request->approvers as $index => $approver) {
                $suratType->approvers()->create([
                    'urutan' => $index + 1,
                    'user_id' => $approver['user_id'] ?? null,
                    'jabatan_label' => $approver['jabatan_label'] ?? '-',
                    'label' => $approver['label'],
                    'metode_ttd' => $approver['metode_ttd'] ?? 'stamp',
                    'is_required' => $approver['is_required'] ?? true,
                ]);
            }
        });

        flash()->success('Jenis surat berhasil dibuat.');
        return redirect()->route('surat-type.index');
    }

    public function edit($id)
    {
        $suratType = SuratType::with('approvers')->findOrFail($id);
        $users = \App\Models\User::role(['hr', 'supervisor'])->get();
        return view('surat-type.create', compact('suratType', 'users'));
    }

    public function update(Request $request, $id)
    {
        $suratType = SuratType::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:surat_types,kode,' . $id,
            'nomor_format' => 'required|array',
            'approvers' => 'required|array|min:1',
            'approvers.*.jabatan_label' => 'required|string|max:100',
            'approvers.*.label' => 'required|string|max:100',
            'approvers.*.user_id' => 'nullable|exists:users,id',
            'approvers.*.metode_ttd' => 'required|in:stamp,append',
        ]);

        DB::transaction(function () use ($request, $suratType) {
            $suratType->update([
                'nama' => $request->nama,
                'kode' => Str::slug($request->kode),
                'deskripsi' => $request->deskripsi,
                'nomor_format' => $request->nomor_format,
                'nomor_reset' => $request->nomor_reset ?? 'yearly',
                'is_active' => $request->boolean('is_active'),
            ]);

            $suratType->approvers()->delete();
            foreach ($request->approvers as $index => $approver) {
                $suratType->approvers()->create([
                    'urutan' => $index + 1,
                    'user_id' => $approver['user_id'] ?? null,
                    'jabatan_label' => $approver['jabatan_label'] ?? '-',
                    'label' => $approver['label'],
                    'metode_ttd' => $approver['metode_ttd'] ?? 'stamp',
                    'is_required' => $approver['is_required'] ?? true,
                ]);
            }
        });

        flash()->success('Jenis surat berhasil diperbarui.');
        return redirect()->route('surat-type.index');
    }

    public function destroy($id)
    {
        $suratType = SuratType::findOrFail($id);

        if ($suratType->surats()->exists()) {
            flash()->error('Jenis surat tidak bisa dihapus karena sudah digunakan.');
            return redirect()->back();
        }

        $suratType->delete();
        flash()->success('Jenis surat berhasil dihapus.');
        return redirect()->route('surat-type.index');
    }

    public function toggle($id)
    {
        $suratType = SuratType::findOrFail($id);
        $suratType->update(['is_active' => !$suratType->is_active]);

        return response()->json(['success' => true, 'is_active' => $suratType->is_active]);
    }
}
