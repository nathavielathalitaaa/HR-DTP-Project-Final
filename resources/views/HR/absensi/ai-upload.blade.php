@extends('layouts.master')

@section('content')
<style>
    .import-page-wrapper { font-family: 'Poppins', sans-serif; color: #1a1a1a; max-width: 1200px; margin: 0 auto; padding: 20px 0; }
    .import-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .title-wrapper { display: flex; align-items: center; gap: 12px; margin-bottom: 4px; }
    .import-header-title { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: #1a1a1a; margin: 0; }
    .ai-badge { background: linear-gradient(135deg, #8b5cf6, #d946ef); color: white; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; letter-spacing: 0.5px; }
    .import-header-subtitle { font-size: 14px; color: #64748b; margin: 0; }
    .import-breadcrumb { list-style: none; display: flex; align-items: center; gap: 8px; padding: 0; margin: 0; font-size: 14px; }
    .import-breadcrumb a { text-decoration: none; color: #64748b; transition: color 0.2s; }
    .import-breadcrumb a:hover { color: #1a1a1a; }
    .import-breadcrumb li.active { color: #1a1a1a; font-weight: 600; }

    .import-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(24px);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        margin-bottom: 24px;
    }

    .drop-zone {
        width: 100%;
        height: 240px;
        border: 2px dashed #cbd5e1;
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #F8FAFC;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        padding: 20px;
    }
    .drop-zone:hover, .drop-zone.dragover { background: #F0F4F2; border-color: #8b5cf6; }
    .drop-zone-icon { color: #8b5cf6; margin-bottom: 12px; width: 56px; height: 56px; }
    .drop-zone-text { font-size: 16px; font-weight: 600; color: #475569; margin: 0 0 6px 0; }
    .drop-zone-subtext { font-size: 13px; color: #94a3b8; margin: 0; }
    .file-input-hidden { display: none; }

    .file-info-card {
        display: none; /* Default hidden */
        margin-top: 16px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .file-info-icon {
        color: #8b5cf6;
        background: #f3e8ff;
        padding: 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .file-info-details {
        display: flex;
        flex-direction: column;
        text-align: left;
    }
    .file-info-name { font-size: 15px; font-weight: 600; color: #1a1a1a; margin: 0 0 2px 0; word-break: break-all;}
    .file-info-size { font-size: 13px; color: #64748b; margin: 0; }

    .form-actions { display: flex; gap: 16px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9; flex-wrap: wrap; justify-content: center;}
    .btn-primary {
        background: #9CA3AF; /* Disabled state by default */
        color: white; border: none; border-radius: 9999px; padding: 14px 32px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 600; cursor: not-allowed; transition: background 0.2s, transform 0.1s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
    }
    .btn-primary.active { background: #4F6560; cursor: pointer; }
    .btn-primary.active:hover { background: #3d504c; }
    .btn-primary.active:active { transform: scale(0.98); }
    .btn-primary.loading { background: #94a3b8 !important; cursor: not-allowed !important; opacity: 0.8; }
</style>

<div class="import-page-wrapper">
    <div class="import-header">
        <div>
            <div class="title-wrapper">
                <h1 class="import-header-title">AI Attendance Recap</h1>
                <span class="ai-badge"><i data-lucide="sparkles" style="width:12px;height:12px;"></i> AI Powered</span>
            </div>
            <p class="import-header-subtitle">Upload fingerprint Excel file to be analyzed and automatically recorded by Groq AI.</p>
        </div>
        <ul class="import-breadcrumb">
            <li><a href="{{ route('hr/absensi/page') }}">Absensi</a></li>
            <li class="active">\ AI Recap</li>
        </ul>
    </div>

    <div class="import-card">
        <form action="{{ route('hr/absensi/ai/analyze') }}" method="POST" enctype="multipart/form-data" id="ai-form">
            @csrf
            
            <div style="max-width: 600px; margin: 0 auto;">
                <div class="drop-zone" id="drop-zone">
                    <i data-lucide="brain-circuit" class="drop-zone-icon"></i>
                    <p class="drop-zone-text">Select Fingerprint Excel File</p>
                    <p class="drop-zone-subtext">Click or Drag & Drop file in .xls, .xlsx format (Max 5MB)</p>
                    <input type="file" name="file" id="file-upload" accept=".xlsx,.xls" required class="file-input-hidden">
                </div>

                <div class="file-info-card" id="file-info-card" style="display:none">
                    <div class="file-info-icon">
                        <i data-lucide="file-spreadsheet" style="width:24px;height:24px;"></i>
                    </div>
                    <div class="file-info-details">
                        <p class="file-info-name" id="file-info-name">nama-file.xlsx</p>
                        <p class="file-info-size" id="file-info-size">2.5 MB</p>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary" id="btn-submit" disabled style="background:#9CA3AF;cursor:not-allowed;opacity:0.6">
                        <i data-lucide="sparkles" style="width:18px;height:18px;" id="btn-icon"></i> 
                        <span id="btn-text">Select file first...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const fileInput    = document.getElementById('file-upload');
    const dropZone     = document.getElementById('drop-zone');
    const fileInfoCard = document.getElementById('file-info-card');
    const fileInfoName = document.getElementById('file-info-name');
    const fileInfoSize = document.getElementById('file-info-size');
    const btnSubmit    = document.getElementById('btn-submit');

    // Klik dropzone → trigger input file
    dropZone.addEventListener('click', function(e) {
        if (e.target !== fileInput) fileInput.click();
    });

    // File dipilih via input
    fileInput.addEventListener('change', function() {
        handleFile(this.files[0]);
    });

    // Drag & drop
    ['dragenter', 'dragover'].forEach(ev => {
        dropZone.addEventListener(ev, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = '#4F6560';
            dropZone.style.background  = '#EDF4F0';
        });
    });

    ['dragleave', 'drop'].forEach(ev => {
        dropZone.addEventListener(ev, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = '#C4D4CE';
            dropZone.style.background  = '#F6FAF8';
        });
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.style.borderColor = '#C4D4CE';
        dropZone.style.background  = '#F6FAF8';
        const files = e.dataTransfer.files;
        if (files.length) {
            fileInput.files = files;
            handleFile(files[0]);
        }
    });

    function handleFile(file) {
        if (!file) return;

        // Validasi ekstensi
        const ext = file.name.split('.').pop().toLowerCase();
        if (!['xls', 'xlsx'].includes(ext)) {
            alert('File format must be .xls or .xlsx');
            fileInput.value = '';
            return;
        }

        // Tampilkan info file
        const size = file.size < 1024 * 1024
            ? (file.size / 1024).toFixed(1) + ' KB'
            : (file.size / 1024 / 1024).toFixed(1) + ' MB';

        fileInfoName.textContent = file.name;
        fileInfoSize.textContent = size;
        fileInfoCard.style.display = 'flex';

        // Aktifkan tombol
        btnSubmit.disabled             = false;
        btnSubmit.style.background     = '#4F6560';
        btnSubmit.style.cursor         = 'pointer';
        btnSubmit.style.opacity        = '1';
        document.getElementById('btn-text').textContent = 'Analyze with AI';
    }

    // Loading state saat submit
    document.querySelector('form').addEventListener('submit', function() {
        if (!fileInput.files.length) return;
        btnSubmit.disabled         = true;
        document.getElementById('btn-text').textContent = 'Analyzing...';
        btnSubmit.style.background = '#9CA3AF';
        btnSubmit.style.cursor     = 'not-allowed';
    });

});
</script>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }
</style>
@endpush
@endsection