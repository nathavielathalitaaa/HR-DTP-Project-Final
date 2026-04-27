<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; color: #1a1a1a; margin: 0; padding: 30px; }
  .header { text-align: center; border-bottom: 2px solid #04A54C; padding-bottom: 16px; margin-bottom: 24px; }
  .header h2 { font-size: 16px; margin: 0 0 4px; color: #04A54C; }
  .header p { margin: 2px 0; font-size: 11px; color: #555; }
  .info-grid { display: table; width: 100%; margin-bottom: 24px; }
  .info-row { display: table-row; }
  .info-label { display: table-cell; width: 140px; font-weight: bold; padding: 3px 0; color: #555; font-size: 11px; }
  .info-value { display: table-cell; padding: 3px 0; font-size: 11px; }
  .ttd-section { margin-top: 32px; }
  .ttd-section h3 { font-size: 12px; font-weight: bold; color: #04A54C; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 16px; }
  .ttd-grid { display: table; width: 100%; }
  .ttd-col { display: table-cell; text-align: center; vertical-align: top; padding: 0 8px; border-right: 1px solid #f0f0f0; }
  .ttd-col:last-child { border-right: none; }
  .ttd-label { font-size: 10px; font-weight: bold; color: #555; margin-bottom: 8px; }
  .ttd-box { height: 70px; display: flex; align-items: center; justify-content: center; margin-bottom: 6px; }
  .ttd-box img { max-height: 65px; max-width: 120px; object-fit: contain; }
  .ttd-empty { height: 70px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 10px; }
  .ttd-name { font-size: 10px; font-weight: bold; border-top: 1px solid #333; padding-top: 4px; margin-top: 4px; }
  .ttd-date { font-size: 9px; color: #888; }
  .footer { margin-top: 40px; font-size: 9px; color: #aaa; text-align: center; border-top: 1px solid #f0f0f0; padding-top: 12px; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; background: #dcfce7; color: #166534; }
</style>
</head>
<body>

<div class="header">
  <h2>LEMBAR PERSETUJUAN DOKUMEN</h2>
  <p><strong>{{ $surat->nomor_surat }}</strong></p>
  <p>HR Sinergi Hotel &amp; Villa</p>
</div>

<div class="info-grid">
  <div class="info-row">
    <div class="info-label">Jenis Dokumen</div>
    <div class="info-value">: {{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Perihal</div>
    <div class="info-value">: {{ $surat->perihal }}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Pembuat</div>
    <div class="info-value">: {{ $surat->user->name ?? '-' }}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Tanggal Dibuat</div>
    <div class="info-value">: {{ $surat->created_at->format('d M Y') }}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Status</div>
    <div class="info-value">: <span class="badge">Disetujui Penuh</span></div>
  </div>
</div>

<div class="ttd-section">
  <h3>TANDA TANGAN PERSETUJUAN</h3>
  <div class="ttd-grid">
    @foreach($steps as $step)
    <div class="ttd-col">
      <div class="ttd-label">{{ $step['label'] }}</div>
      <div class="ttd-box">
        @if($step['ttd_base64'])
          <img src="{{ $step['ttd_base64'] }}" alt="TTD">
        @else
          <div class="ttd-empty">Tidak ada TTD</div>
        @endif
      </div>
      <div class="ttd-name">{{ $step['name'] }}</div>
      <div class="ttd-date">{{ $step['actioned_at'] ?? '-' }}</div>
      @if($step['catatan'])
        <div class="ttd-date" style="margin-top:4px;font-style:italic;">"{{ $step['catatan'] }}"</div>
      @endif
    </div>
    @endforeach
  </div>
</div>

<div class="footer">
  Dokumen ini digenerate otomatis oleh sistem HR Sinergi &bull; {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>
