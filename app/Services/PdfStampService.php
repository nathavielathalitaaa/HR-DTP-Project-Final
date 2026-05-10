<?php

namespace App\Services;

use App\Models\Surat;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class PdfStampService
{
    public function stamp(Surat $surat): string
    {
        \Log::info('PdfStampService@stamp starting', ['surat_id' => $surat->id]);

        // 1. Get approval steps
        $approvals = $surat->approvals()
            ->where('status', 'approved')
            ->with('approver.profile')
            ->get();
        
        \Log::info('Found approvals for stamping', ['count' => $approvals->count()]);

        // 2. Check if any approval requires append mode
        if ($approvals->contains('metode_ttd', 'append')) {
            \Log::info('Append mode detected, directly creating pengesahan.');
            return $this->appendSignatures($surat);
        }

        // 3. Get coordinates from $surat->ttd_coordinates
        $coords = $surat->ttd_coordinates;
        if (!$coords || empty($coords)) {
            \Log::warning('Stamp warning: Coordinates not found, falling back to append mode.', ['surat_id' => $surat->id]);
            return $this->appendSignatures($surat);
        }

        // 4. Load original PDF using FPDI
        $originalPdfPath = storage_path('app/public/' . $surat->file_pdf);
        if (!file_exists($originalPdfPath)) {
            \Log::error('Stamp failed: Original PDF not found', ['path' => $originalPdfPath]);
            throw new \Exception("Original PDF file not found at " . $originalPdfPath);
        }

        $pdf = new Fpdi();

        try {
            $pageCount = $pdf->setSourceFile($originalPdfPath);
        } catch (\Exception $e) {
            \Log::warning('FPDI cannot read PDF, falling back to append mode');
            return $this->appendSignatures($surat);
        }

        try {
            \Log::info('PDF loaded', ['pageCount' => $pageCount]);

            // 4. For each page
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                // Find any coordinates for this page
                foreach ($approvals as $approval) {
                    $jabatan = $approval->jabatan;
                    $coordKey = null;
                    foreach (array_keys($coords) as $key) {
                        if (strtolower($key) === strtolower($jabatan)) {
                            $coordKey = $key;
                            break;
                        }
                    }
                    if ($coordKey && $coords[$coordKey]['page'] == $pageNo) {
                        $coord = $coords[$coordKey];
                        
                        // Convert percentage to pixels/points
                        $x = ($coord['x'] / 100) * $size['width'];
                        $y = ($coord['y'] / 100) * $size['height'];

                        $foundPath = $this->getSignaturePath($approval);
                        
                        if ($foundPath && ($approval->metode_ttd ?? 'stamp') === 'stamp') {
                            \Log::info('Stamping signature', ['jabatan' => $jabatan, 'path' => $foundPath]);
                            $w = 40; 
                            $h = 20;
                            $pdf->Image($foundPath, $x - ($w/2), $y - ($h/2), $w, $h);
                        }
                    }
                }
            }

            // 5. Save
            $finalFilename = $surat->id . '_final_' . time() . '.pdf';
            $finalDir = storage_path('app/public/final-pdf');
            if (!is_dir($finalDir)) mkdir($finalDir, 0755, true);
            $finalPath = $finalDir . '/' . $finalFilename;
            $pdf->Output('F', $finalPath);

            \Log::info('Stamp completed successfully', ['finalPath' => $finalPath]);

            return 'final-pdf/' . $finalFilename;
        } catch (\Exception $e) {
            \Log::error('FPDI Error during stamp: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fallback: Append signatures to a new page
     */
    private function appendSignatures(Surat $surat): string
    {
        \Log::info('PdfStampService@appendSignatures starting', ['surat_id' => $surat->id]);

        $approvals = $surat->approvals()
            ->where('status', 'approved')
            ->with('approver.profile')
            ->get();

        $pdf = new Fpdi();
        $pdf->AddPage('P', 'A4');

        // Logo
        $logoPath = public_path('assets/images/logo-sinergi.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 85, 10, 40);
            $pdf->Ln(25);
        } else {
            $pdf->Ln(20);
        }

        // Title
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'LEMBAR PENGESAHAN', 0, 1, 'C');

        // Surat number
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->Cell(0, 8, $surat->nomor_surat ?? '-', 0, 1, 'C');

        // Perihal
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 8, $surat->perihal ?? '-', 0, 1, 'C');

        // Divider line
        $pdf->Ln(5);
        $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(15);

        // Signature columns
        $count = $approvals->count();
        if ($count > 0) {
            $pageWidth = 210;
            $margin = 20;
            $cellWidth = ($pageWidth - ($margin * 2)) / $count;
            $startY = $pdf->GetY();

            foreach ($approvals as $i => $approval) {
                $x = $margin + ($i * $cellWidth);
                
                // Jabatan
                $pdf->SetXY($x, $startY);
                $pdf->SetFont('Helvetica', 'B', 10);
                $pdf->Cell($cellWidth, 5, strtoupper($approval->jabatan ?? ''), 0, 0, 'C');

                // Signature Image
                $foundPath = $this->getSignaturePath($approval);
                if ($foundPath) {
                    $pdf->Image($foundPath, $x + ($cellWidth/2) - 15, $startY + 10, 30, 15);
                }

                // Horizontal line
                $lineY = $startY + 30;
                $pdf->Line($x + 5, $lineY, $x + $cellWidth - 5, $lineY);

                // Name
                $pdf->SetXY($x, $lineY + 2);
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->Cell($cellWidth, 5, $approval->approver?->name ?? '-', 0, 0, 'C');
            }
            $pdf->Ln(40);
        }

        // Footer
        $pdf->SetY(-30);
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->Cell(0, 10, 'Dokumen ini adalah lampiran lembar pengesahan digital.', 0, 0, 'C');

        $finalFilename = $surat->id . '_pengesahan_' . time() . '.pdf';
        $finalDir = storage_path('app/public/final-pdf');
        if (!is_dir($finalDir)) mkdir($finalDir, 0755, true);
        $finalPath = $finalDir . '/' . $finalFilename;
        $pdf->Output('F', $finalPath);

        return 'final-pdf/' . $finalFilename;
    }

    private function getSignaturePath($approval): ?string
    {
        $profile = $approval->approver ? $approval->approver->profile : null;
        $signature = null;

        if ($profile && $profile->signature_path) {
            $signature = $profile->signature_path;
        } elseif ($approval->ttd_snapshot) {
            $signature = $approval->ttd_snapshot;
        } elseif ($profile && $profile->ttd_path) {
            $signature = $profile->ttd_path;
        }

        if (!$signature) return null;

        $paths = [
            storage_path('app/public/' . $signature),
            storage_path('app/private/' . $signature),
            storage_path('app/private/private/' . $signature),
        ];

        foreach ($paths as $p) {
            if (file_exists($p) && !is_dir($p)) {
                return $p;
            }
        }

        return null;
    }
}
