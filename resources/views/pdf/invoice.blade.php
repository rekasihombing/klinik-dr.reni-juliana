<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $tagihan->nomor_tagihan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .clinic-info {
            font-size: 11px;
            color: #666;
        }
        .invoice-info {
            margin-bottom: 20px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 3px 0;
            vertical-align: top;
        }
        .patient-info {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .patient-info h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        .items-table td.number {
            text-align: right;
        }
        .items-table td.center {
            text-align: center;
        }
        .total-section {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row.grand-total {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #333;
            border-top: 2px solid #333;
            padding: 10px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #333;
        }
        .no-items {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="clinic-name">{{ $clinicInfo['name'] }}</div>
        <div class="clinic-info">
            {{ $clinicInfo['address'] }}<br>
            Telp: {{ $clinicInfo['phone'] }}
        </div>
    </div>

    <!-- Invoice Info -->
    <div class="invoice-info">
        <table>
            <tr>
                <td width="50%">
                    <strong>No. Invoice:</strong> {{ $tagihan->nomor_tagihan }}<br>
                    <strong>Tanggal:</strong> {{ $tagihan->created_at->format('d/m/Y H:i') }}
                </td>
                <td width="50%">
                    <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}<br>
                    @if($tagihan->tanggal_bayar)
                    <strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d/m/Y H:i') }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Patient Info -->
    <div class="patient-info">
        <h3>Informasi Pasien</h3>
        <strong>Nama:</strong> {{ $patient->nama ?? $patient->nama_lengkap ?? 'Tidak tersedia' }}<br>
        @if($patient->umur)
        <strong>Umur:</strong> {{ $patient->umur }} tahun<br>
        @endif
        @if($patient->jenis_kelamin)
        <strong>Jenis Kelamin:</strong> {{ $patient->jenis_kelamin }}<br>
        @endif
        @if($patient->no_telp)
        <strong>No. Telp:</strong> {{ $patient->no_telp }}<br>
        @endif
        @if($patient->alamat)
        <strong>Alamat:</strong> {{ $patient->alamat }}
        @endif
    </div>

    <!-- Medicine Items -->
    @if(count($medicineItems) > 0)
    <div class="section-title">Obat</div>
    <table class="items-table">
        <thead>
            <tr>
                <th width="40%">Nama Obat</th>
                <th width="15%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga</th>
                <th width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicineItems as $item)
            <tr>
                <td>
                    {{ $item['name'] }}
                    @if($item['dosis'] && $item['dosis'] !== '-')
                    <br><small>Dosis: {{ $item['dosis'] }}</small>
                    @endif
                    @if($item['catatan'])
                    <br><small>{{ $item['catatan'] }}</small>
                    @endif
                </td>
                <td class="center">{{ $item['quantity'] }}</td>
                <td class="center">{{ $item['unit'] }}</td>
                <td class="number">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                <td class="number">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="section-title">Obat</div>
    <div class="no-items">Tidak ada obat</div>
    @endif

    <!-- Treatment Items -->
    @if(count($treatmentItems) > 0)
    <div class="section-title">Tindakan</div>
    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Nama Tindakan</th>
                <th width="15%">Jumlah</th>
                <th width="15%">Harga</th>
                <th width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($treatmentItems as $item)
            <tr>
                <td>
                    {{ $item['name'] }}
                    @if($item['catatan'])
                    <br><small>{{ $item['catatan'] }}</small>
                    @endif
                </td>
                <td class="center">{{ $item['quantity'] }}</td>
                <td class="number">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                <td class="number">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="section-title">Tindakan</div>
    <div class="no-items">Tidak ada tindakan</div>
    @endif

    <!-- Total Section -->
    <div class="clearfix">
        <div class="total-section">
            <div class="total-row">
                <span>Total Biaya Obat:</span>
                <span>Rp {{ number_format($medicineTotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Total Biaya Tindakan:</span>
                <span>Rp {{ number_format($treatmentTotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL PEMBAYARAN:</span>
                <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda kepada {{ $clinicInfo['name'] }}</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>