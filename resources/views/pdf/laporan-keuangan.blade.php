<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Keuangan Klinik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2A4482;
            padding-bottom: 10px;
        }
        
        .header h1 {
            color: #2A4482;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        
        .statistics {
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        .stat-label {
            font-size: 11px;
            color: #2A4482;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .chart-section {
            margin-bottom: 30px;
        }
        
        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .chart-table th,
        .chart-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .chart-table th {
            background-color: #2A4482;
            color: white;
            font-weight: bold;
        }
        
        .chart-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .detail-section {
            margin-top: 30px;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .detail-table th,
        .detail-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        
        .detail-table th {
            background-color: #2A4482;
            color: white;
            font-weight: bold;
        }
        
        .detail-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .currency {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #e8f4f8 !important;
        }
        
        h2 {
            color: #2A4482;
            border-bottom: 1px solid #2A4482;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN KLINIK</h1>
        <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
        <p>Jenis Laporan: {{ ucfirst($filterType) }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span>{{ $generatedAt }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Filter:</span>
            <span>{{ ucfirst($filterType) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span>{{ $startDate }} s/d {{ $endDate }}</span>
        </div>
    </div>

    <div class="statistics">
        <h2>Ringkasan Keuangan</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Income</div>
                <div class="stat-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Rata-rata {{ ucfirst($filterType) }}</div>
                <div class="stat-value">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Income Tertinggi</div>
                <div class="stat-value">Rp {{ number_format($incomeTertinggi, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Income Terendah</div>
                <div class="stat-value">Rp {{ number_format($incomeTerendah, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="chart-section">
        <h2>Data Income Per {{ ucfirst($filterType) }}</h2>
        <table class="chart-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Income</th>
                    <th>Persentase dari Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chartData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->label }}</td>
                    <td class="currency">Rp {{ number_format($data->income, 0, ',', '.') }}</td>
                    <td class="currency">{{ $totalIncome > 0 ? number_format(($data->income / $totalIncome) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2"><strong>TOTAL</strong></td>
                    <td class="currency"><strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong></td>
                    <td class="currency"><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

   @if(count($detailTransaksi) > 0)
    <div class="detail-section page-break">
        <h2>Detail Transaksi</h2>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. Tagihan</th>
                    <th>Nama Pasien</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailTransaksi as $index => $transaksi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $transaksi->id }}</td>
                    <td>{{ $transaksi->nama_pasien }}</td>
                    <td class="currency">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->status ?? 'Selesai' }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4"><strong>TOTAL KESELURUHAN</strong></td>
                    <td class="currency"><strong>Rp {{ number_format($detailTransaksi->sum('subtotal'), 0, ',', '.') }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini dicetak secara otomatis pada {{ $generatedAt }}</p>
        <p>Klinik - Sistem Manajemen Keuangan</p>
    </div>
</body>
</html>