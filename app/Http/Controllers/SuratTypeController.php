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
        $approverUsers = \App\Models\User::with(['profile', 'roles'])
            ->where(function($q) {
                // Include: all hr and supervisor
                $q->whereHas('roles', function($roleQuery) {
                    $roleQuery->whereIn('name', ['hr', 'supervisor']);
                })
                // Include: anyone (including staff) who has jabatan set
                ->orWhereHas('profile', function($profileQuery) {
                    $profileQuery->whereNotNull('jabatan')
                                 ->where('jabatan', '!=', '');
                });
            })
            ->get()
            ->map(function($user) {
                return [
                    'id'     => $user->id,
                    'name'   => $user->name,
                    'role'   => $user->roles->first()?->name ?? 'staff',
                    'jabatan'=> $user->profile?->jabatan ?? '-',
                ];
            });

        return view('surat-type.create', compact('approverUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:surat_types,kode',
            'nomor_format' => 'required|array',
            'approvers' => 'required|array|min:1',
            'approvers.*.jabatan_label' => 'required|string|max:100',
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
                    'metode_ttd' => $approver['metode_ttd'] ?? 'stamp',
                    'is_required' => isset($approver['is_required']),
                ]);
            }
        });

        flash()->success('Document type created successfully.');
        return redirect()->route('surat-type.index');
    }

    public function edit($id)
    {
        $suratType = SuratType::with('approvers')->findOrFail($id);
        
        $approverUsers = \App\Models\User::with(['profile', 'roles'])
            ->where(function($q) {
                $q->whereHas('roles', function($roleQuery) {
                    $roleQuery->whereIn('name', ['hr', 'supervisor']);
                })
                ->orWhereHas('profile', function($profileQuery) {
                    $profileQuery->whereNotNull('jabatan')
                                 ->where('jabatan', '!=', '');
                });
            })
            ->get()
            ->map(function($user) {
                return [
                    'id'     => $user->id,
                    'name'   => $user->name,
                    'role'   => $user->roles->first()?->name ?? 'staff',
                    'jabatan'=> $user->profile?->jabatan ?? '-',
                ];
            });

        return view('surat-type.create', compact('suratType', 'approverUsers'));
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
                    'metode_ttd' => $approver['metode_ttd'] ?? 'stamp',
                    'is_required' => isset($approver['is_required']),
                ]);
            }
        });

        flash()->success('Document type updated successfully.');
        return redirect()->route('surat-type.index');
    }

    public function destroy($id)
    {
        $suratType = SuratType::findOrFail($id);

        DB::transaction(function () use ($suratType) {
            // delete associated documents and their approvals
            $surats = $suratType->surats;
            foreach ($surats as $surat) {
                // delete approvals first
                $surat->approvals()->delete();
                
                // delete files from storage if they exist
                $filesToDelete = [$surat->file_pdf, $surat->cover_pdf_path, $surat->final_pdf_path];
                foreach ($filesToDelete as $file) {
                    if ($file) {
                        \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($file);
                        if (file_exists(storage_path('app/public/' . $file))) {
                            @unlink(storage_path('app/public/' . $file));
                        }
                    }
                }
                
                $surat->delete();
            }

            // delete approver configurations
            $suratType->approvers()->delete();
            
            // finally delete the document type
            $suratType->delete();
        });

        flash()->success('Document type and all associated records deleted successfully.');
        return redirect()->route('surat-type.index');
    }

    public function toggle($id)
    {
        $suratType = SuratType::findOrFail($id);
        $suratType->update(['is_active' => !$suratType->is_active]);

        return response()->json(['success' => true, 'is_active' => $suratType->is_active]);
    }
}
