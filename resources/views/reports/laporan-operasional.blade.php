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
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 0;
            margin: 0;
        }

        .page {
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 12mm;
            background: white;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .logo {
            width: 45px;
            height: 45px;
            border: 2px solid #000;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .clinic-name {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }

        .clinic-info {
            font-size: 9px;
            color: #000;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin: 8px 0 4px;
            text-decoration: underline;
        }

        .report-period {
            font-size: 10px;
            color: #000;
            font-weight: normal;
        }

        /* Report Meta */
        .report-meta {
            width: 100%;
            margin-bottom: 16px;
            font-size: 9px;
            border: 1px solid #000;
            border-collapse: collapse;
            overflow-x: auto;
        }

        .meta-row {
            display: flex;
            width: 100%;
        }

        .meta-cell {
            flex: 1;
            padding: 6px;
            border-right: 1px solid #000;
            vertical-align: top;
            min-width: 0;
        }

        .meta-cell:last-child {
            border-right: none;
        }

        .meta-label {
            font-weight: bold;
            margin-bottom: 2px;
        }

        /* Statistics Section */
        .stats-section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            padding: 5px;
            background: #f0f0f0;
            border: 1px solid #000;
            text-align: center;
        }

        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1px;
            border: 1px solid #000;
        }

        .stat-card {
            flex: 1;
            min-width: 180px;
            border-right: 1px solid #000;
            padding: 10px;
            text-align: center;
            background: white;
        }

        .stat-card:last-child {
            border-right: none;
        }

        .stat-title {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            margin: 4px 0;
        }

        .stat-growth {
            font-size: 8px;
            font-style: italic;
            margin-top: 4px;
        }

        /* Visit Frequency */
        .visit-frequency {
            margin-top: 6px;
        }

        .frequency-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2px 0;
            font-size: 8px;
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
            width: 6px;
            height: 6px;
            border: 1px solid #000;
            margin-right: 4px;
            flex-shrink: 0;
        }

        .frequency-dot.filled {
            background: #000;
        }

        .frequency-value {
            font-weight: bold;
        }

        /* Chart Section */
        .chart-section {
            margin-bottom: 18px;
            border: 1px solid #000;
            padding: 10px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #000;
            flex-wrap: wrap;
        }

        .chart-title {
            font-size: 11px;
            font-weight: bold;
        }

        .chart-average {
            font-size: 9px;
            font-style: italic;
        }

        .chart-container {
            height: 120px;
            position: relative;
            border: 1px solid #000;
            padding: 8px;
        }

        .chart-bars {
            display: flex;
            align-items: end;
            justify-content: space-around;
            height: 80px;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding: 0 4px;
        }

        .chart-bar {
            background: #000;
            width: 16px;
            min-height: 4px;
            position: relative;
        }

        .chart-bar-value {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 7px;
            font-weight: bold;
        }

        .chart-labels {
            display: flex;
            justify-content: space-around;
            font-size: 8px;
            font-weight: bold;
            padding: 0 4px;
        }

        /* Summary Table */
        .summary-section {
            margin-bottom: 18px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            table-layout: fixed;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
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
        }

        .summary-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* Column widths */
        .summary-table th:nth-child(1),
        .summary-table td:nth-child(1) {
            width: 40%;
        }

        .summary-table th:nth-child(2),
        .summary-table td:nth-child(2) {
            width: 15%;
        }

        .summary-table th:nth-child(3),
        .summary-table td:nth-child(3) {
            width: 45%;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8px;
        }

        .footer p {
            margin-bottom: 2px;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .page {
                padding: 8mm;
            }
            
            .stats-grid {
                flex-direction: column;
            }
            
            .stat-card {
                min-width: 100%;
                border-right: none;
                border-bottom: 1px solid #000;
            }
            
            .stat-card:last-child {
                border-bottom: none;
            }
            
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
            
            .meta-row {
                flex-wrap: wrap;
            }
            
            .meta-cell {
                min-width: 45%;
                border-bottom: 1px solid #000;
            }
        }

        /* Print Optimization */
        @media print {
            .page {
                margin: 0;
                padding: 10mm;
                box-shadow: none;
                max-width: none;
                width: 100%;
            }
            
            body {
                font-size: 10px;
            }
            
            .chart-container {
                height: 100px;
            }
            
            .chart-bars {
                height: 70px;
            }
            
            .stat-number {
                font-size: 16px;
            }
            
            .clinic-name {
                font-size: 14px;
            }
            
            .report-title {
                font-size: 12px;
            }
            
            .section-title {
                font-size: 11px;
            }
        }

        @page {
            size: A4;
            margin: 0;
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
            <div class="meta-row">
                <div class="meta-cell">
                    <div class="meta-label">Tanggal Cetak:</div>
                    <div>{{ $reportDate }}</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Waktu Cetak:</div>
                    <div>{{ $reportTime }} WIB</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Halaman:</div>
                    <div>1 dari 1</div>
                </div>
                <div class="meta-cell">
                    <div class="meta-label">Dicetak Oleh:</div>
                    <div>Administrator</div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section no-break">
            <div class="section-title">I. RINGKASAN STATISTIK PASIEN</div>
            <div class="stats-grid">
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
                            <div class="frequency-label">
                                <div class="frequency-dot filled"></div>
                                <span>1 kali</span>
                            </div>
                            <span class="frequency-value">{{ $visitFrequency['once'] }}%</span>
                        </div>
                        <div class="frequency-item">
                            <div class="frequency-label">
                                <div class="frequency-dot"></div>
                                <span>2-3 kali</span>
                            </div>
                            <span class="frequency-value">{{ $visitFrequency['twoToThree'] }}%</span>
                        </div>
                        <div class="frequency-item">
                            <div class="frequency-label">
                                <div class="frequency-dot"></div>
                                <span>4+ kali</span>
                            </div>
                            <span class="frequency-value">{{ $visitFrequency['more'] }}%</span>
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
            <p style="margin-top: 8px; font-style: italic;">
                <strong>Catatan:</strong> Data dalam laporan ini bersifat rahasia dan hanya untuk keperluan internal klinik
            </p>
        </div>
    </div>

    <script>
        // Generate chart data from Laravel controller
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData ?? []);
            
            if (!chartData || chartData.length === 0) {
                document.getElementById('patientChart').innerHTML = '<div style="text-align: center; padding: 20px;">Tidak ada data untuk ditampilkan</div>';
                return;
            }

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
                bar.style.height = `${maxPatients > 0 ? (data.patients / maxPatients) * 100 : 0}%`;
                
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