<!DOCTYPE html>
<html lang="en">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;800&display=swap" rel="stylesheet">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Pelaporan Infrastruktur</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <?php include 'components/header.php'; ?>

    <main>
        <div class="banner-container">
            <img class="banner" src="../assets/banner.png" alt="Banner">
            <div class="text-container">
                <div class="title">
                    Sistem Informasi<br />
                    Pelaporan Infrastruktur
                </div>
                <div class="description">
                    Bukan hanya infrastruktur saja, laporkan segala anomali yang ada di lingkungan sekitarmu! Laporanmu
                    akan disampaikan kepada pemerintah desa setempat.
                </div>
                <a href="lapor.php">
                    <div class="report-button">
                        <span>Ajukan Laporan</span>
                    </div>
                </a>
            </div>
        </div>

        <h2 class="info-title-alur">Bagaimana laporanmu diproses?</h2>

        <div class="info-container">
            <div class="info-item">
                <div class="info-title">1. Buat Laporan</div>
                <div class="info-description">Laporkan segala keluhanmu dengan lengkap dan jelas.</div>
            </div>
            <div class="info-item">
                <div class="info-title">2. Proses Verifikasi</div>
                <div class="info-description">Laporanmu akan diverifikasi dan diteruskan kepada pihak berwenang.</div>
            </div>
            <div class="info-item">
                <div class="info-title">3. Proses Tindak Lanjut</div>
                <div class="info-description">Laporanmu akan ditindak lanjut dan kamu akan mendapatkan pesan balasan melalui email.</div>
            </div>
            <div class="info-item">
                <div class="info-title">4. Laporan Selesai</div>
                <div class="info-description">Laporanmu telah selesai ditindak lanjuti.</div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

</body>

</html>
