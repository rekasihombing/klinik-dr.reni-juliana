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
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            background: #fff;
            padding: 0;
            margin: 0;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .logo {
            width: 55px;
            height: 55px;
            border: 2px solid #000;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }

        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }

        .clinic-info {
            font-size: 11px;
            color: #000;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin: 12px 0 8px;
            text-decoration: underline;
        }

        .report-period {
            font-size: 12px;
            color: #000;
            font-weight: normal;
        }

        /* Report Meta */
        .report-meta {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            font-size: 11px;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .meta-row {
            display: table-row;
        }

        .meta-cell {
            display: table-cell;
            padding: 10px;
            border-right: 1px solid #000;
            vertical-align: top;
        }

        .meta-cell:last-child {
            border-right: none;
        }

        .meta-label {
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* Statistics Section */
        .stats-section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-bottom: 12px;
            padding: 8px;
            background: #f0f0f0;
            border: 1px solid #000;
            text-align: center;
        }

        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .stats-row {
            display: table-row;
        }

        .stat-card {
            display: table-cell;
            border: 1px solid #000;
            padding: 15px;
            text-align: center;
            vertical-align: top;
            width: 33.33%;
        }

        .stat-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin: 8px 0;
        }

        .stat-growth {
            font-size: 10px;
            font-style: italic;
            margin-top: 8px;
        }

        /* Visit Frequency */
        .visit-frequency {
            margin-top: 12px;
        }

        .frequency-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            font-size: 10px;
            border-bottom: 1px dotted #999;
        }

        .frequency-item:last-child {
            border-bottom: none;
        }

        .frequency-label {
            display: flex;
            align-items: center;
        }

        .frequency-dot {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 6px;
        }

        .frequency-dot.filled {
            background: #000;
        }

        .frequency-value {
            font-weight: bold;
        }

        /* Chart Section */
        .chart-section {
            margin-bottom: 25px;
            border: 1px solid #000;
            padding: 15px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #000;
        }

        .chart-title {
            font-size: 13px;
            font-weight: bold;
        }

        .chart-average {
            font-size: 11px;
            font-style: italic;
        }

        .chart-container {
            height: 160px;
            position: relative;
            border: 1px solid #000;
            padding: 12px;
        }

        .chart-bars {
            display: flex;
            align-items: end;
            justify-content: space-around;
            height: 110px;
            margin-bottom: 12px;
            border-bottom: 1px solid #000;
            padding: 0 8px;
        }

        .chart-bar {
            background: #000;
            width: 24px;
            min-height: 8px;
            position: relative;
        }

        .chart-bar-value {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            font-weight: bold;
        }

        .chart-labels {
            display: flex;
            justify-content: space-around;
            font-size: 10px;
            font-weight: bold;
            padding: 0 8px;
        }

        /* Summary Table */
        .summary-section {
            margin-bottom: 25px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        .summary-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 12px;
        }

        .summary-table .number {
            text-align: right;
            font-weight: bold;
        }

        .summary-table td:first-child {
            font-weight: bold;
        }

        .summary-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
        }

        .footer p {
            margin-bottom: 4px;
        }

        .signature-section {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 200px;
            border: 1px solid #000;
            padding: 15px;
        }

        .signature-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 60px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
        }

        .signature-name {
            font-size: 11px;
            font-weight: bold;
        }

        /* Print Optimization - Utama untuk PDF */
        @media print {
            .page {
                margin: 0;
                padding: 15mm;
                box-shadow: none;
            }
            
            body {
                font-size: 11px;
            }
            
            .chart-container {
                height: 140px;
            }
            
            .chart-bars {
                height: 90px;
            }
            
            .stat-number {
                font-size: 22px;
            }
            
            .clinic-name {
                font-size: 18px;
            }
            
            .report-title {
                font-size: 16px;
            }
            
            .section-title {
                font-size: 13px;
            }
        }

        @page {
            size: A4;
            margin: 0;
        }

        /* Optimasi khusus untuk PDF rendering */
        .no-break {
            page-break-inside: avoid;
        }

        .break-before {
            page-break-before: always;
        }

        .break-after {
            page-break-after: always;
        }

        /* Pastikan elemen penting tidak terpotong */
        .stats-section,
        .chart-section,
        .summary-section {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header no-break">
            <div class="header-top">
                <div class="logo">K+</div>
                <div>
                    <div class="clinic-name">{{ $clinicName ?? 'KLINIK SEHAT BERSAMA' }}</div>
                    <div class="clinic-info">
                        {{ $clinicAddress ?? 'Jl. Kesehatan No. 123, Jakarta Selatan 12345' }}<br>
                        Telp: {{ $clinicPhone ?? '(021) 1234-5678' }} | Fax: (021) 1234-5679<br>
                    </div>
                </div>
            </div>
            <div class="report-title">LAPORAN OPERASIONAL KLINIK</div>
            <div class="report-period">
                Periode: {{ ucfirst($period ?? 'Bulanan') }} ({{ $dateFrom ?? '01 Desember 2024' }} s/d {{ $dateTo ?? '31 Desember 2024' }})
            </div>
        </div>

        <!-- Report Meta -->
        <div class="report-meta no-break">
            <div class="meta-row">
                <div class="meta-cell">
                    <div class="meta-label">Tanggal Cetak:</div>
                    <div>{{ $reportDate ?? date('d F Y') }}</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Waktu Cetak:</div>
                    <div>{{ $reportTime ?? date('H:i:s') }} WIB</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Halaman:</div>
                    <div>1 dari 1</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Dicetak Oleh:</div>
                    <div>{{ $printedBy ?? 'Administrator' }}</div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section no-break">
            <div class="section-title">I. RINGKASAN STATISTIK PASIEN</div>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-title">Total Pasien Terdaftar</div>
                        <div class="stat-number">{{ number_format($totalPatients ?? 1250) }}</div>
                        <div class="stat-growth">+{{ $patientGrowth ?? 12 }}% dari periode sebelumnya</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Pasien Baru</div>
                        <div class="stat-number">{{ number_format($newPatients ?? 180) }}</div>
                        <div class="stat-growth">Dalam periode ini</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Frekuensi Kunjungan</div>
                        <div class="visit-frequency">
                            <div class="frequency-item">
                                <div class="frequency-label">
                                    <div class="frequency-dot filled"></div>
                                    <span>1 kali</span>
                                </div>
                                <span class="frequency-value">{{ $visitFrequency['once'] ?? 45 }}%</span>
                            </div>
                            <div class="frequency-item">
                                <div class="frequency-label">
                                    <div class="frequency-dot"></div>
                                    <span>2-3 kali</span>
                                </div>
                                <span class="frequency-value">{{ $visitFrequency['twoToThree'] ?? 35 }}%</span>
                            </div>
                            <div class="frequency-item">
                                <div class="frequency-label">
                                    <div class="frequency-dot"></div>
                                    <span>4+ kali</span>
                                </div>
                                <span class="frequency-value">{{ $visitFrequency['more'] ?? 20 }}%</span>
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
                <div class="chart-title">Jumlah Kunjungan per Hari - {{ ucfirst($period ?? 'Bulanan') }}</div>
                <div class="chart-average">Rata-rata: {{ $averagePatients ?? 42 }} pasien/hari</div>
            </div>
            
            <div class="chart-container">
                <div class="chart-bars" id="patientChart">
                    <!-- Chart akan di-generate oleh JavaScript -->
                </div>
                <div class="chart-labels" id="chartLabels">
                    <!-- Labels akan di-generate oleh JavaScript -->
                </div>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="summary-section">
            <div class="section-title">III. ANALISIS DETAIL OPERASIONAL</div>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">INDIKATOR KINERJA</th>
                        <th style="width: 15%;">NILAI</th>
                        <th style="width: 45%;">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Pasien Terdaftar</td>
                        <td class="number">{{ number_format($totalPatients ?? 1250) }}</td>
                        <td>Keseluruhan pasien yang terdaftar di database klinik sampai dengan periode ini</td>
                    </tr>
                    <tr>
                        <td>Pasien Baru (30 hari terakhir)</td>
                        <td class="number">{{ number_format($newPatients ?? 180) }}</td>
                        <td>Menunjukkan pertumbuhan {{ $patientGrowth > 0 ? 'positif' : 'negatif' }} sebesar {{ abs($patientGrowth ?? 12) }}% dibandingkan periode sebelumnya</td>
                    </tr>
                    <tr>
                        <td>Rata-rata Kunjungan per Hari</td>
                        <td class="number">{{ $averagePatients ?? 42 }}</td>
                        <td>Berdasarkan periode {{ $dateFrom ?? '01 Des 2024' }} sampai {{ $dateTo ?? '31 Des 2024' }}</td>
                    </tr>
                    <tr>
                        <td>Tingkat Retensi Pasien</td>
                        <td class="number">{{ 100 - ($visitFrequency['once'] ?? 45) }}%</td>
                        <td>Persentase pasien yang melakukan kunjungan lebih dari 1 kali (indikator loyalitas)</td>
                    </tr>
                    <tr>
                        <td>Pasien Kunjungan Tunggal</td>
                        <td class="number">{{ $visitFrequency['once'] ?? 45 }}%</td>
                        <td>Pasien yang hanya melakukan 1 kali kunjungan dalam periode ini</td>
                    </tr>
                    <tr>
                        <td>Pasien Reguler (2-3 kunjungan)</td>
                        <td class="number">{{ $visitFrequency['twoToThree'] ?? 35 }}%</td>
                        <td>Pasien dengan frekuensi kunjungan sedang, menunjukkan kebutuhan perawatan berkelanjutan</td>
                    </tr>
                    <tr>
                        <td>Pasien Setia (4+ kunjungan)</td>
                        <td class="number">{{ $visitFrequency['more'] ?? 20 }}%</td>
                        <td>Pasien dengan kunjungan intensif, indikator kondisi kronis atau kepuasan tinggi</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p><strong>LAPORAN OPERASIONAL KLINIK - {{ strtoupper($period ?? 'BULANAN') }}</strong></p>
            <p>Dokumen ini dibuat secara otomatis oleh Sistem Informasi Klinik</p>
            <p>Dicetak pada {{ $reportDate ?? date('d F Y') }} pukul {{ $reportTime ?? date('H:i:s') }} WIB</p>
            <p style="margin-top: 12px; font-style: italic;">
                <strong>Catatan:</strong> Data dalam laporan ini bersifat rahasia dan hanya untuk keperluan internal klinik
            </p>
        </div>
    </div>

    <script>
        // Generate chart data for PDF
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = [
                { label: 'Sen', patients: 38 },
                { label: 'Sel', patients: 45 },
                { label: 'Rab', patients: 52 },
                { label: 'Kam', patients: 41 },
                { label: 'Jum', patients: 48 },
                { label: 'Sab', patients: 35 },
                { label: 'Min', patients: 25 }
            ];

            const maxPatients = Math.max(...chartData.map(d => d.patients));
            const chartBars = document.getElementById('patientChart');
            const chartLabels = document.getElementById('chartLabels');

            // Clear existing content
            chartBars.innerHTML = '';
            chartLabels.innerHTML = '';

            // Generate bars
            chartData.forEach(data => {
                const bar = document.createElement('div');
                bar.className = 'chart-bar';
                bar.style.height = `${(data.patients / maxPatients) * 100}%`;
                
                const value = document.createElement('div');
                value.className = 'chart-bar-value';
                value.textContent = data.patients;
                bar.appendChild(value);
                
                chartBars.appendChild(bar);
            });

            // Generate labels
            chartData.forEach(data => {
                const label = document.createElement('span');
                label.textContent = data.label;
                chartLabels.appendChild(label);
            });
        });
    </script>
</body>
</html>