<?php

namespace App\Services;

use App\Models\Surat;
use App\Models\DocumentApproval;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ApprovalCoverService
{
    public function generateCover(Surat $surat): string
    {
        $documentType = 'surat_' . $surat->jenis_surat;

        $steps = DocumentApproval::where('document_type', $documentType)
            ->where('document_id', $surat->id)
            ->orderBy('step_order')
            ->with('approver')
            ->get();

        $stepsWithTtd = $steps->map(function ($step) {
            $ttdBase64 = null;
            if ($step->ttd_snapshot) {
                // ttd_path format: "ttd/2.png" → full path: storage/app/private/ttd/2.png
                $ttdPath = storage_path('app/private/private/' . $step->ttd_snapshot);
                if (file_exists($ttdPath)) {
                    $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath));
                }
            }
            return [
                'label'       => $step->label,
                'name'        => $step->approver?->name ?? '-',
                'actioned_at' => $step->actioned_at?->format('d M Y'),
                'catatan'     => $step->catatan,
                'ttd_base64'  => $ttdBase64,
                'status'      => $step->status,
            ];
        });

        $pdf = Pdf::loadView('surat.cover-approval', [
            'surat' => $surat,
            'steps' => $stepsWithTtd,
        ])->setPaper('A4', 'portrait');

        $filename = 'cover_approval_' . $surat->id . '_' . time() . '.pdf';
        $path = 'surat/covers/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}