<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = $_POST['nik'];
    $email = $_POST['email'];
    $judul_laporan = $_POST['judul_laporan'];
    $kategori_laporan = $_POST['kategori_laporan'];
    $isi_laporan = $_POST['isi_laporan'];
    $tanggal_kejadian = $_POST['tanggal_kejadian'];
    $lokasi_kejadian = $_POST['lokasi_kejadian'];

    // Handle file upload
    $lampiran = null;
    if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] == UPLOAD_ERR_OK) {
        $lampiran = file_get_contents($_FILES['lampiran']['tmp_name']);
    }

    // Check NIK
    $stmt = $conn->prepare("SELECT * FROM warga WHERE NIK = ?");
    $stmt->bind_param("i", $nik);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header('Location: lapor.php?message=NIK tidak terdaftar! Pastikan kamu memasukkan NIK yang benar.&type=error' .
               '&nik=' . urlencode($nik) .
               '&email=' . urlencode($email) .
               '&judul_laporan=' . urlencode($judul_laporan) .
               '&kategori_laporan=' . urlencode($kategori_laporan) .
               '&isi_laporan=' . urlencode($isi_laporan) .
               '&tanggal_kejadian=' . urlencode($tanggal_kejadian) .
               '&lokasi_kejadian=' . urlencode($lokasi_kejadian)
        );
        exit();
    } else {

        $sql = "INSERT INTO laporan (NIK, ID_STATUS, ID_KATEGORI, EMAIL_PELAPOR, JUDUL_LAPORAN, ISI_LAPORAN, TANGGAL_KEJADIAN, LOKASI_KEJADIAN, LAMPIRAN, TANGGAL_SUBMIT) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        $id_status = 0; // assuming 'Terkirim' status
        $stmt->bind_param("iiissssss", $nik, $id_status, $kategori_laporan, $email, $judul_laporan, $isi_laporan, $tanggal_kejadian, $lokasi_kejadian, $lampiran);

        if ($stmt->execute()) {
            header('Location: lapor.php?message=Laporan berhasil dikirim!&type=success');
            exit();
        } else {
            header('Location: lapor.php?message=Error: Gagal menyimpan laporan.&type=error');
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
