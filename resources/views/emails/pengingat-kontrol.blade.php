<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Jadwal Kontrol - {{ $clinic_name }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2A4482;">Pengingat Jadwal Kontrol - {{ $clinic_name }}</h2>
        <p>Yth. {{ $nama_pasien }},</p>
        <p>Kami mengingatkan Anda tentang jadwal kontrol ulang di klinik kami:</p>
        <ul>
            <li><strong>Tanggal Kontrol:</strong> {{ $tanggal_kontrol }}</li>
            <li><strong>Catatan:</strong> {{ $catatan }}</li>
        </ul>
        <p>Silakan hubungi kami jika Anda perlu mengubah jadwal atau memiliki pertanyaan.</p>
        <p>Terima kasih,<br>{{ $clinic_name }}</p>
    </div>
</body>
</html>