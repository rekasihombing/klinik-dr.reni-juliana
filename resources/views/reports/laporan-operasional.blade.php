<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Operasional Klinik</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            background: #fff;
            padding: 0;
            margin: 0;
        }

        .page {
            width: 180mm;
            min-height: 260mm;
            margin: 0 auto;
            padding: 20mm 15mm;
            background: white;
            position: relative;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
        }

        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .header-logo {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }

        .logo {
            width: 50px;
            height: 50px;
            border: 2px solid #000;
            display: inline-block;
            text-align: center;
            line-height: 46px;
            font-weight: bold;
            font-size: 16px;
        }

        .header-info {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding-left: 15px;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }

        .clinic-info {
            font-size: 10px;
            color: #000;
            line-height: 1.3;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin: 8px 0 4px;
            text-decoration: underline;
        }

        .report-period {
            font-size: 11px;
            color: #000;
            font-weight: normal;
        }

        /* Report Meta */
        .report-meta {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .report-meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-meta td {
            padding: 6px 8px;
            border-right: 1px solid #000;
            vertical-align: top;
            width: 25%;
        }

        .report-meta td:last-child {
            border-right: none;
        }

        .meta-label {
            font-weight: bold;
            margin-bottom: 2px;
        }

        /* Statistics Section */
        .stats-section {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            padding: 6px;
            background: #f0f0f0;
            border: 1px solid #000;
            text-align: center;
        }

        .stats-container {
            border: 1px solid #000;
        }

        .stats-row {
            display: table;
            width: 100%;
        }

        .stat-card {
            display: table-cell;
            width: 33.33%;
            border-right: 1px solid #000;
            padding: 12px 8px;
            text-align: center;
            vertical-align: top;
        }

        .stat-card:last-child {
            border-right: none;
        }

        .stat-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            margin: 6px 0;
            color: #000;
        }

        .stat-growth {
            font-size: 9px;
            font-style: italic;
            margin-top: 4px;
            color: #666;
        }

        /* Visit Frequency */
        .visit-frequency {
            margin-top: 8px;
        }

        .frequency-item {
            margin-bottom: 4px;
            font-size: 9px;
            position: relative;
            padding-left: 15px;
        }

        .frequency-item:last-child {
            margin-bottom: 0;
        }

        .frequency-dot {
            position: absolute;
            left: 0;
            top: 2px;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
        }

        .frequency-dot.filled {
            background: #000;
        }

        .frequency-value {
            font-weight: bold;
            float: right;
        }

        /* Chart Section */
        .chart-section {
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 12px;
            page-break-inside: avoid;
        }

        .chart-header {
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #000;
        }

        .chart-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .chart-average {
            font-size: 9px;
            font-style: italic;
            color: #666;
        }

        .chart-container {
            height: 140px; /* Diperbesar untuk ruang grid */
            position: relative;
            border: 1px solid #000;
            padding: 8px;
            margin-top: 8px;
            background: #fff;
        }

        .chart-grid {
            position: absolute;
            top: 8px;
            left: 8px;
            right: 8px;
            bottom: 20px;
            z-index: 1;
        }

        .grid-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 1px;
            background: #d0d0d0;
            z-index: 1;
        }

        .grid-label {
            position: absolute;
            left: -30px;
            font-size: 8px;
            color: #666;
            line-height: 1;
        }

        .chart-bars {
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 100px; /* Ruang untuk bar */
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding: 0 8px;
            background: #f9f9f9;
            z-index: 2;
        }

        .chart-bar {
            background: #2c3e50; /* Warna biru tua untuk kontras */
            width: 18px; /* Lebar bar sedikit lebih besar */
            min-height: 4px;
            position: relative;
            border: 1px solid #000;
            border-radius: 2px; /* Sudut sedikit membulat */
        }

        .chart-bar-value {
            position: absolute;
            top: -18px; /* Jarak nilai dari bar */
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            font-weight: bold;
            color: #000;
            background: #fff; /* Latar putih untuk kejelasan */
            padding: 0 2px;
        }

        .chart-labels {
            display: flex;
            justify-content: space-around;
            font-size: 9px;
            font-weight: bold;
            padding: 0 8px;
        }

        .chart-label {
            flex: 1;
            text-align: center;
            color: #000;
            word-wrap: break-word; /* Cegah label terpotong */
            max-width: 30px; /* Batasi lebar label */
        }

        .chart-error {
            text-align: center;
            padding: 30px 0;
            color: #666;
            font-size: 10px;
            background: #f9f9f9;
        }

        /* Summary Table */
        .summary-section {
            margin-bottom: 20px;
            margin-top: 10px;
            page-break-before: auto;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .summary-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }

        .summary-table .number {
            text-align: right;
            font-weight: bold;
        }

        .summary-table td:first-child {
            font-weight: bold;
            width: 35%;
        }

        .summary-table td:nth-child(2) {
            width: 15%;
        }

        .summary-table td:nth-child(3) {
            width: 50%;
        }

        .summary-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 9px;
            page-break-inside: avoid;
        }

        .footer p {
            margin-bottom: 3px;
        }

        .footer-note {
            margin-top: 8px;
            font-style: italic;
            font-size: 8px;
        }

        /* PDF-specific styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .page {
                margin: 0;
                padding: 15mm 10mm 20mm 10mm;
                width: auto;
                min-height: auto;
            }
            
            .stats-row {
                display: block;
            }
            
            .stat-card {
                display: block;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #000;
                margin-bottom: 10px;
            }
            
            .stat-card:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }

            .summary-section {
                margin-top: 15mm;
            }

            .chart-container {
                height: 120px;
            }

            .chart-bars {
                height: 80px;
            }
        }

        @page {
            size: A4;
            margin: 15mm 20mm 20mm 20mm;
        }

        @page :first {
            margin-top: 15mm;
        }

        /* Page break controls */
        .no-break {
            page-break-inside: avoid;
        }

        .break-before {
            page-break-before: always;
        }

        .break-after {
            page-break-after: always;
        }

        .page-break-margin {
            margin-top: 15mm;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header no-break">
            <div class="header-top">
                <div class="header-logo">
                    <div class="logo">K+</div>
                </div>
                <div class="header-info">
                    <div class="clinic-name">{{ $clinicName ?? 'KLINIK SEHAT BERSAMA' }}</div>
                    <div class="clinic-info">
                        {{ $clinicAddress ?? 'Jl. Kesehatan No. 123, Medan' }}<br>
                        Telp: {{ $clinicPhone ?? '(061) 123-4567' }} | Fax: (061) 123-4568<br>
                        Email: info@kliniksehat.com | Website: www.kliniksehat.com
                    </div>
                </div>
            </div>
            <div class="report-title">LAPORAN OPERASIONAL KLINIK</div>
            <div class="report-period">
                Periode: {{ ucfirst($period) }} ({{ $dateFrom }} s/d {{ $dateTo }})
            </div>
        </div>

        <!-- Report Meta -->
        <div class="report-meta no-break">
            <table>
                <tr>
                    <td>
                        <div class="meta-label">Tanggal Cetak:</div>
                        <div>{{ $reportDate }}</div>
                    </td>
                    <td>
                        <div class="meta-label">Waktu Cetak:</div>
                        <div>{{ $reportTime }} WIB</div>
                    </td>
                    <td>
                        <div class="meta-label">Halaman:</div>
                        <div>1 dari 1</div>
                    </td>
                    <td>
                        <div class="meta-label">Dicetak Oleh:</div>
                        <div>Administrator</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section no-break">
            <div class="section-title">I. RINGKASAN STATISTIK PASIEN</div>
            <div class="stats-container">
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-title">Total Pasien Terdaftar</div>
                        <div class="stat-number">{{ number_format($totalPatients) }}</div>
                        <div class="stat-growth">
                            @if($patientGrowth > 0)
                                +{{ $patientGrowth }}% dari periode sebelumnya
                            @elseif($patientGrowth < 0)
                                {{ $patientGrowth }}% dari periode sebelumnya
                            @else
                                Tidak ada perubahan
                            @endif
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Pasien Baru</div>
                        <div class="stat-number">{{ number_format($newPatients) }}</div>
                        <div class="stat-growth">Dalam periode ini</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Frekuensi Kunjungan</div>
                        <div class="visit-frequency">
                            <div class="frequency-item">
                                <div class="frequency-dot filled"></div>
                                <span>1 kali</span>
                                <span class="frequency-value">{{ $visitFrequency['once'] }}%</span>
                            </div>
                            <div class="frequency-item">
                                <div class="frequency-dot"></div>
                                <span>2-3 kali</span>
                                <span class="frequency-value">{{ $visitFrequency['twoToThree'] }}%</span>
                            </div>
                            <div class="frequency-item">
                                <div class="frequency-dot"></div>
                                <span>4+ kali</span>
                                <span class="frequency-value">{{ $visitFrequency['more'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-section no-break">
            <div class="section-title">II. GRAFIK AKTIVITAS PASIEN HARIAN</div>
            <div class="chart-header">
                <div class="chart-title">Jumlah Kunjungan per {{ ucfirst($period) }}</div>
                <div class="chart-average">Rata-rata: {{ $averagePatients }} pasien/hari</div>
            </div>
            
            <div class="chart-container">
                @php
                    // Hitung nilai maksimum untuk skala grafik
                    $maxPatients = count($chartData) > 0 ? max(array_column($chartData, 'patients')) : 1;
                    // Data default jika chartData kosong
                    if (empty($chartData)) {
                        $chartData = [
                            ['label' => 'Senin', 'patients' => 50],
                            ['label' => 'Selasa', 'patients' => 60],
                            ['label' => 'Rabu', 'patients' => 45],
                            ['label' => 'Kamis', 'patients' => 55],
                            ['label' => 'Jumat', 'patients' => 40],
                        ];
                        $maxPatients = 60; // Update maxPatients untuk data default
                    }
                    // Hitung skala grid (misalnya, 4 garis)
                    $gridStep = ceil($maxPatients / 4);
                    $gridValues = [0, $gridStep, $gridStep * 2, $gridStep * 3, $gridStep * 4];
                @endphp

                <!-- Grid Lines -->
                <div class="chart-grid">
                    @foreach ($gridValues as $index => $value)
                        <div class="grid-line" style="bottom: {{ ($index * 25) }}%;"></div>
                        <div class="grid-label" style="bottom: {{ ($index * 25) - 2 }}%;">{{ $value }}</div>
                    @endforeach
                </div>

                <!-- Bars and Labels -->
                <div class="chart-bars">
                    @foreach ($chartData as $data)
                        <div class="chart-bar" style="height: {{ ($data['patients'] / $maxPatients) * 100 }}%;">
                            <div class="chart-bar-value">{{ $data['patients'] }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="chart-labels">
                    @foreach ($chartData as $data)
                        <span class="chart-label">{{ $data['label'] }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="summary-section page-break-margin">
            <div class="section-title">III. ANALISIS DETAIL OPERASIONAL</div>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>INDIKATOR KINERJA</th>
                        <th>NILAI</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Pasien Terdaftar</td>
                        <td class="number">{{ number_format($totalPatients) }}</td>
                        <td>Keseluruhan pasien yang terdaftar di database klinik sampai dengan periode ini</td>
                    </tr>
                    <tr>
                        <td>Pasien Baru (30 hari terakhir)</td>
                        <td class="number">{{ number_format($newPatients) }}</td>
                        <td>
                            @if($patientGrowth > 0)
                                Menunjukkan pertumbuhan positif sebesar {{ $patientGrowth }}% dibandingkan periode sebelumnya
                            @elseif($patientGrowth < 0)
                                Menunjukkan penurunan sebesar {{ abs($patientGrowth) }}% dibandingkan periode sebelumnya
                            @else
                                Tidak ada perubahan dibandingkan periode sebelumnya
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Rata-rata Kunjungan per Hari</td>
                        <td class="number">{{ $averagePatients }}</td>
                        <td>Berdasarkan periode {{ $dateFrom }} sampai {{ $dateTo }}</td>
                    </tr>
                    <tr>
                        <td>Tingkat Retensi Pasien</td>
                        <td class="number">{{ 100 - $visitFrequency['once'] }}%</td>
                        <td>Persentase pasien yang melakukan kunjungan lebih dari 1 kali (indikator loyalitas)</td>
                    </tr>
                    <tr>
                        <td>Pasien Kunjungan Tunggal</td>
                        <td class="number">{{ $visitFrequency['once'] }}%</td>
                        <td>Pasien yang hanya melakukan 1 kali kunjungan dalam periode ini</td>
                    </tr>
                    <tr>
                        <td>Pasien Reguler (2-3 kunjungan)</td>
                        <td class="number">{{ $visitFrequency['twoToThree'] }}%</td>
                        <td>Pasien dengan frekuensi kunjungan sedang, menunjukkan kebutuhan perawatan berkelanjutan</td>
                    </tr>
                    <tr>
                        <td>Pasien Setia (4+ kunjungan)</td>
                        <td class="number">{{ $visitFrequency['more'] }}%</td>
                        <td>Pasien dengan kunjungan intensif, indikator kondisi kronis atau kepuasan tinggi</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p><strong>LAPORAN OPERASIONAL KLINIK - {{ strtoupper($period) }}</strong></p>
            <p>Dokumen ini dibuat secara otomatis oleh Sistem Informasi Klinik</p>
            <p>Dicetak pada {{ $reportDate }} pukul {{ $reportTime }} WIB</p>
            <div class="footer-note">
                <strong>Catatan:</strong> Data dalam laporan ini bersifat rahasia dan hanya untuk keperluan internal klinik
            </div>
        </div>
    </div>
</body>
</html>