<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; padding: 20px; }
  .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #04A54C; padding-bottom: 12px; }
  .header h2 { font-size: 16px; color: #04A54C; margin-bottom: 4px; }
  .header p { font-size: 11px; color: #555; }
  .summary { display: flex; width: 100%; margin-bottom: 16px; border-collapse: collapse; }
  .summary-table { width: 100%; text-align: center; margin-bottom: 16px; }
  .summary-table td { border: 1px solid #e2e8f0; padding: 8px; border-radius: 6px; width: 20%; }
  .summary-table .num { font-size: 18px; font-weight: bold; }
  .summary-table .lbl { font-size: 10px; color: #888; margin-top: 2px; }
  table { width: 100%; border-collapse: collapse; margin-top: 8px; }
  thead tr { background: #04A54C; color: white; }
  thead th { padding: 7px 8px; text-align: left; font-size: 11px; font-weight: bold; }
  tbody tr:nth-child(even) { background: #f8fafb; }
  tbody td { padding: 6px 8px; border-bottom: 1px solid #f0f0f0; font-size: 10px; }
  .footer { margin-top: 16px; font-size: 9px; color: #aaa; text-align: right; }
</style>
</head>
<body>

<div class="header">
  <h2>REKAP ABSENSI KARYAWAN BULANAN</h2>
  <p>HR Sinergi Hotel &amp; Villa &nbsp;|&nbsp; Periode: {{ \Carbon\Carbon::parse($bulan . '-01')->format('F Y') }}</p>
</div>

<table class="summary-table">
  <tr>
    <td>
      <div class="num" style="color:#1a1a1a;">{{ $ringkasan['total_karyawan'] }}</div>
      <div class="lbl">Total Karyawan</div>
    </td>
    <td>
      <div class="num" style="color:#16a34a;">{{ $ringkasan['total_hadir'] }}</div>
      <div class="lbl">Total Hari Hadir</div>
    </td>
    <td>
      <div class="num" style="color:#dc2626;">{{ $ringkasan['total_alfa'] }}</div>
      <div class="lbl">Total Tidak Hadir</div>
    </td>
    <td>
      <div class="num" style="color:#d97706;">{{ $ringkasan['total_terlambat_kali'] }}</div>
      <div class="lbl">Total Terlambat (Kali)</div>
    </td>
    <td>
      <div class="num" style="color:#7c3aed;">{{ $ringkasan['total_lembur_jam'] }}</div>
      <div class="lbl">Total Lembur (Jam)</div>
    </td>
  </tr>
</table>

<table>
  <thead>
    <tr>
      <th width="30">No</th>
      <th width="150">Nama Karyawan</th>
      <th width="100">Departemen</th>
      <th width="80">Hari Dibutuhkan</th>
      <th width="60">Hadir</th>
      <th width="70">Tidak Hadir</th>
      <th width="70">Terlambat (Kali)</th>
      <th width="80">Terlambat (Menit)</th>
      <th width="70">Lembur (Jam)</th>
    </tr>
  </thead>
  <tbody>
    @forelse($rekapList as $i => $absensi)
    @php $data = $absensi->rekap; @endphp
    <tr>
      <td>{{ $i + 1 }}</td>
      <td>{{ $absensi->user?->name ?? '-' }}</td>
      <td>{{ $absensi->user?->profile?->jabatan ?? '-' }}</td>
      <td>{{ $data['hari_dibutuhkan'] ?? 0 }}</td>
      <td>{{ $data['hari_hadir'] ?? 0 }}</td>
      <td>{{ $data['hari_tidak_hadir'] ?? 0 }}</td>
      <td>{{ $data['terlambat_count'] ?? 0 }}</td>
      <td>{{ $data['terlambat_menit'] ?? 0 }}</td>
      <td>{{ isset($data['lembur_menit']) ? round($data['lembur_menit']/60, 2) : 0 }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="9" style="text-align:center;padding:20px;color:#aaa;">Tidak ada data rekap absensi</td>
    </tr>
    @endforelse
  </tbody>
</table>

<div class="footer">
  Digenerate otomatis oleh sistem HR Sinergi &bull; {{ now()->format('d M Y H:i') }}
</div>

</body>
</html>
