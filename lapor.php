<!-- lapor.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Pelaporan Infrastruktur</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <main>
        <?php

        if (isset($_GET['message'])) {
            echo '<div class="';
            echo ($_GET['type'] == 'success') ? 'success-message' : 'error-message';
            echo '">' . $_GET['message'] . '</div>';
        }
        ?>

        <h2 class="page-title">Buat Laporanmu</h2>
        <form action="submit_laporan.php" method="post" enctype="multipart/form-data" class="lapor-form">
            <div class="form-group">
                <label for="nik">NIK:</label>
                <input type="text" id="nik" name="nik" placeholder="Masukkan NIK kamu" required
                       value="<?php echo isset($_GET['nik']) ? htmlspecialchars($_GET['nik']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email kamu" required
                       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="judul_laporan">Judul Laporan:</label>
                <input type="text" id="judul_laporan" name="judul_laporan" placeholder="Masukkan judul laporan" required
                       value="<?php echo isset($_GET['judul_laporan']) ? htmlspecialchars($_GET['judul_laporan']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="kategori_laporan">Kategori Laporan:</label>
                <select id="kategori_laporan" name="kategori_laporan" required>
                    <?php
                    
                    // Connect to the database and fetch categories
                    include 'config.php';
                    $sql = "SELECT ID_KATEGORI, KATEGORI_KEJADIAN FROM kategori_kejadian";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = (isset($_GET['kategori_laporan']) && $_GET['kategori_laporan'] == $row['ID_KATEGORI']) ? 'selected' : '';
                            echo "<option value='" . $row["ID_KATEGORI"] . "' $selected>" . $row["KATEGORI_KEJADIAN"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="isi_laporan">Isi Laporan:</label>
                <textarea id="isi_laporan" name="isi_laporan" placeholder="Masukkan isi laporan" required><?php echo isset($_GET['isi_laporan']) ? htmlspecialchars($_GET['isi_laporan']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="tanggal_kejadian">Tanggal Kejadian:</label>
                <input type="date" id="tanggal_kejadian" name="tanggal_kejadian" required
                       value="<?php echo isset($_GET['tanggal_kejadian']) ? $_GET['tanggal_kejadian'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="lokasi_kejadian">Lokasi Kejadian:</label>
                <input type="text" id="lokasi_kejadian" name="lokasi_kejadian" placeholder="Masukkan lokasi kejadian" required
                       value="<?php echo isset($_GET['lokasi_kejadian']) ? htmlspecialchars($_GET['lokasi_kejadian']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="lampiran">Lampiran:</label>
                <input type="file" id="lampiran" name="lampiran">
            </div>

            <div class="container">
                <input type="submit" value="Laporkan" class="submit-button">
            </div>

        </form>
    </main>

    <?php include 'components/footer.php'; ?>

</body>
</html>
