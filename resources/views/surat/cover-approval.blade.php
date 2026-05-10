<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    color: #1a1a1a;
    padding: 32px;
    background: #fff;
  }

  /* Header */
  .header {
    text-align: center;
    border-bottom: 2px solid #4F6560;
    padding-bottom: 16px;
    margin-bottom: 24px;
  }
  .header h2 {
    font-size: 18px;
    font-weight: bold;
    color: #4F6560;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .header p { font-size: 12px; color: #555; }

  /* Info grid */
  .info-table { width: 100%; margin-bottom: 24px; border-collapse: collapse; }
  .info-table td { padding: 4px 0; font-size: 12px; }
  .info-table td:first-child { width: 160px; color: #666; font-weight: bold; }

  /* TTD Section */
  .ttd-section { margin-top: 32px; }
  .ttd-section-title {
    font-size: 13px;
    font-weight: bold;
    color: #4F6560;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 8px;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* TTD columns — gunakan tabel agar DomPDF render dengan benar */
  .ttd-table { width: 100%; border-collapse: collapse; }
  .ttd-table td {
    text-align: center;
    vertical-align: top;
    padding: 0 12px;
    border-right: 1px solid #f0f0f0;
  }
  .ttd-table td:last-child { border-right: none; }

  .ttd-label {
    font-size: 11px;
    font-weight: bold;
    color: #555;
    margin-bottom: 10px;
    text-transform: uppercase;
  }

  /* Kotak TTD */
  .ttd-box {
    width: 100%;
    height: 80px;
    border: 1px dashed #ccc;
    border-radius: 6px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #fafafa;
  }
  .ttd-box img {
    max-width: 100%;
    max-height: 75px;
    object-fit: contain;
  }
  .ttd-empty {
    width: 100%;
    height: 80px;
    border: 1px dashed #ddd;
    border-radius: 6px;
    margin-bottom: 8px;
    background: #f9f9f9;
  }

  .ttd-name {
    font-size: 11px;
    font-weight: bold;
    color: #1a1a1a;
    border-top: 1px solid #333;
    padding-top: 4px;
    margin-top: 4px;
  }
  .ttd-date { font-size: 10px; color: #888; margin-top: 3px; }
  .ttd-note {
    font-size: 10px;
    font-style: italic;
    color: #666;
    margin-top: 4px;
  }

  /* Badge */
  .badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    background: #d1ead9;
    color: #1a5c33;
  }

  /* Footer */
  .footer {
    margin-top: 40px;
    font-size: 10px;
    color: #aaa;
    text-align: center;
    border-top: 1px solid #f0f0f0;
    padding-top: 12px;
  }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
  <h2>Lembar Persetujuan Dokumen</h2>
  <p>{{ $settings['company_name'] ?? 'HR Sinergi Hotel & Villa' }}</p>
</div>

{{-- Info Surat --}}
<table class="info-table">
  <tr>
    <td>Nomor Surat</td>
    <td>: <strong>{{ $surat->nomor_surat }}</strong></td>
  </tr>
  <tr>
    <td>Jenis Dokumen</td>
    <td>: {{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</td>
  </tr>
  <tr>
    <td>Perihal</td>
    <td>: {{ $surat->perihal }}</td>
  </tr>
  <tr>
    <td>Pembuat</td>
    <td>: {{ $surat->user->name ?? '-' }}</td>
  </tr>
  <tr>
    <td>Tanggal Dibuat</td>
    <td>: {{ $surat->created_at->format('d M Y') }}</td>
  </tr>
  <tr>
    <td>Status</td>
    <td>: <span class="badge">Disetujui Penuh</span></td>
  </tr>
</table>

{{-- TTD Section --}}
<div class="ttd-section">
  <div class="ttd-section-title">Tanda Tangan Persetujuan</div>

  {{-- Gunakan tabel HTML biasa agar DomPDF render dengan benar --}}
  <table class="ttd-table">
    <tr>
      @foreach($steps as $step)
      <td style="width: {{ count($steps) > 0 ? round(100 / count($steps)) : 100 }}%;">
        <div class="ttd-label">{{ $step['label'] }}</div>

        {{-- Kotak TTD --}}
        @if(!empty($step['ttd_base64']))
          <div class="ttd-box">
            <img src="{{ $step['ttd_base64'] }}" alt="TTD {{ $step['label'] }}">
          </div>
        @else
          <div class="ttd-empty"></div>
        @endif

        <div class="ttd-name">{{ $step['name'] }}</div>
        <div class="ttd-date">{{ $step['actioned_at'] ?? '-' }}</div>

        @if(!empty($step['catatan']))
          <div class="ttd-note">"{{ $step['catatan'] }}"</div>
        @endif
      </td>
      @endforeach
    </tr>
  </table>
</div>

{{-- Footer --}}
<div class="footer">
  {{ $settings['footer_text'] ?? 'Dokumen ini digenerate otomatis oleh sistem HR.' }}
  &bull; {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>
