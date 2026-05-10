@extends('layouts.master')

@section('content')
<style>
    /* Base typography & layouts */
    .import-page-wrapper {
        font-family: 'Poppins', sans-serif;
        color: #1a1a1a;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 0;
    }
    
    .import-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .import-header-title {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 4px 0;
    }
    .import-header-subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }
    .import-breadcrumb {
        list-style: none;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0;
        margin: 0;
        font-size: 14px;
    }
    .import-breadcrumb a {
        text-decoration: none;
        color: #64748b;
        transition: color 0.2s;
    }
    .import-breadcrumb a:hover {
        color: #1a1a1a;
    }
    .import-breadcrumb li.active {
        color: #1a1a1a;
        font-weight: 600;
    }

    /* Card System */
    .import-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(24px);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        padding: 32px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        margin-bottom: 24px;
    }

    /* Warning Card for Skipped Names */
    .warning-card {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .warning-card-header {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }
    .warning-card-icon {
        color: #f59e0b;
        margin-top: 2px;
    }
    .warning-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: #92400e;
        margin: 0 0 6px 0;
    }
    .warning-card-text {
        font-size: 14px;
        color: #92400e;
        margin: 0;
    }
    
    .warning-table-wrapper {
        overflow-x: auto;
    }
    .warning-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .warning-table th {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(253, 230, 138, 0.6);
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
    }
    .warning-table td {
        padding: 16px;
        border-bottom: 1px solid #fef3c7;
        vertical-align: middle;
    }
    .warning-table tr:last-child td {
        border-bottom: none;
    }
    
    .map-select-input {
        width: 100%;
        max-width: 300px;
        background: #F0F4F2;
        border: none;
        border-radius: 9999px;
        padding: 10px 16px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        color: #1a1a1a;
        appearance: none;
        outline: none;
        transition: box-shadow 0.2s;
    }
    .map-select-input:focus {
        box-shadow: 0 0 0 2px #80BB9B;
    }
    .btn-warning-sm {
        background: #f59e0b;
        color: white;
        border: none;
        border-radius: 9999px;
        padding: 8px 16px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-warning-sm:hover {
        background: #d97706;
    }

    /* Steps Grid */
    .steps-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }
    @media (min-width: 768px) {
        .steps-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    .step-item {
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        border-radius: 16px;
        padding: 20px;
        position: relative;
    }
    .step-number {
        position: absolute;
        top: -12px;
        left: -12px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #4F6560;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        border: 4px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .step-title {
        font-weight: 700;
        font-size: 14px;
        color: #1a1a1a;
        margin: 8px 0 4px 0;
    }
    .step-desc {
        font-size: 12px;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 24px;
    }
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    .form-label span.required {
        color: #ef4444;
    }
    .hivi-input {
        width: 100%;
        max-width: 400px;
        background: #F0F4F2;
        border: none;
        border-radius: 9999px;
        padding: 14px 24px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        color: #1a1a1a;
        outline: none;
        transition: box-shadow 0.2s;
        appearance: none;
    }
    .hivi-input:focus {
        box-shadow: 0 0 0 2px #80BB9B;
    }

    /* Drag & Drop Zone */
    .drop-zone {
        width: 100%;
        height: 200px;
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
    .drop-zone:hover, .drop-zone.dragover {
        background: #F0F4F2;
        border-color: #80BB9B;
    }
    .drop-zone-icon {
        color: #94a3b8;
        margin-bottom: 12px;
        width: 48px;
        height: 48px;
    }
    .drop-zone-text {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        margin: 0 0 4px 0;
    }
    .drop-zone-subtext {
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
    }
    .file-input-hidden {
        display: none;
    }

    /* File Display Badge */
    .file-display {
        display: none;
        margin-top: 16px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        color: #4F6560;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .file-display.active {
        display: inline-flex;
    }

    /* Actions */
    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }
    .btn-primary {
        background: #4F6560;
        color: white;
        border: none;
        border-radius: 9999px;
        padding: 13px 28px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-primary:hover {
        background: #3d504c;
    }
    .btn-primary:active {
        transform: scale(0.98);
    }
    .btn-secondary {
        background: white;
        color: #1a1a1a;
        border: 1px solid #E5E7EB;
        border-radius: 9999px;
        padding: 13px 28px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
</style>

<div class="import-page-wrapper">
    <div class="import-header print:hidden">
        <div>
            <h1 class="import-header-title">Import Attendance Recap from Excel</h1>
            <p class="import-header-subtitle">Upload monthly recap file from fingerprint machine</p>
        </div>
        <ul class="import-breadcrumb">
            <li><a href="{{ route('hr/absensi/page') }}">Attendance</a></li>
            <li class="active">\ Import</li>
        </ul>
    </div>

    @if(session('warning_skipped_names'))
    <div class="warning-card">
        <div class="warning-card-header">
            <i data-lucide="alert-triangle" class="warning-card-icon"></i>
            <div>
                <h6 class="warning-card-title">Some Names Not Recognized</h6>
                <p class="warning-card-text">The names below are in Excel but not yet linked to employee data. Please map them to the correct employee.</p>
            </div>
        </div>
        <div class="warning-table-wrapper">
            <table class="warning-table">
                <thead>
                    <tr>
                        <th>Name in Excel (Fingerprint)</th>
                        <th>Choose Employee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('warning_skipped_names') as $index => $namaFinger)
                    <tr id="map-row-{{ $index }}">
                        <td style="font-weight: 600;">{{ $namaFinger }}</td>
                        <td>
                            <select class="map-select-input" id="map-select-{{ $index }}">
                                <option value="">-- Choose Employee --</option>
                                @foreach(\App\Models\User::orderBy('name')->get() as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="button" onclick="saveMapping('{{ $index }}', '{{ addslashes($namaFinger) }}')" class="btn-warning-sm">
                                Save & Map
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="import-card">
        <div class="steps-grid">
            <div class="step-item">
                <div class="step-number">1</div>
                <h6 class="step-title">Select Month</h6>
                <p class="step-desc">Determine the month period for the attendance recap to be imported.</p>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <h6 class="step-title">Prepare Excel</h6>
                <p class="step-desc">Ensure standard column format for fingerprint machine (Sheet "Summary").</p>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <h6 class="step-title">Upload File</h6>
                <p class="step-desc">Upload .xlsx/.xls file. The system will automatically record it to the database.</p>
            </div>
        </div>

        <form action="{{ route('hr/absensi/import/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Select Period / Month <span class="required">*</span></label>
                <select name="bulan" required class="hivi-input">
                    <option value="">-- Select Month --</option>
                    @foreach ($months as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Upload Excel File <span class="required">*</span></label>
                <div class="drop-zone" id="drop-zone" onclick="document.getElementById('file-upload').click()">
                    <i data-lucide="upload-cloud" class="drop-zone-icon"></i>
                    <p class="drop-zone-text">Click or Drag & Drop file here</p>
                    <p class="drop-zone-subtext">Supported formats: .xls, .xlsx (Max 5MB)</p>
                    <input type="file" name="file" id="file-upload" accept=".xlsx,.xls" required class="file-input-hidden" onchange="showFileName(this)">
                    
                    <div id="file-name-display" class="file-display">
                        <i data-lucide="file" style="width:16px;height:16px;"></i> 
                        <span id="file-name-text"></span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i data-lucide="upload" style="width:18px;height:18px;"></i> Start Import
                </button>
                <a href="{{ route('hr/absensi/page') }}" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showFileName(input) {
    const display = document.getElementById('file-name-display');
    const text = document.getElementById('file-name-text');
    if (input.files && input.files[0]) {
        text.textContent = input.files[0].name;
        display.classList.add('active');
    } else {
        display.classList.remove('active');
    }
}

// Drag and drop logic
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-upload');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});
function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
});
['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
});
dropZone.addEventListener('drop', (e) => {
    let dt = e.dataTransfer;
    let files = dt.files;
    if(files.length) {
        fileInput.files = files;
        showFileName(fileInput);
    }
}, false);

function saveMapping(index, namaFingerprint) {
    const userId = document.getElementById('map-select-' + index).value;
    if (!userId) {
        alert('Select an employee first!');
        return;
    }
    
    fetch('{{ route("hr/absensi/import/map") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            user_id: userId,
            nama_fingerprint: namaFingerprint
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            document.getElementById('map-row-' + index).remove();
        } else {
            alert('Gagal: ' + res.message);
        }
    })
    .catch(e => {
        console.error(e);
        alert('Network error occurred.');
    });
}
</script>
@endpush
@endsection
