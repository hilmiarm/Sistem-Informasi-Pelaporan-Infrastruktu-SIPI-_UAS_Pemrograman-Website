<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: adminLogin.php");
    exit;
}

include 'config.php';

// Function to sanitize input
function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

// Konfigurasi pagination
$limit = 10; // Jumlah laporan per halaman
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Halaman saat ini
$offset = ($page - 1) * $limit; // Hitung offset

// Query database untuk mendapatkan total jumlah laporan
$totalQuery = "SELECT COUNT(*) as total FROM laporan";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalLaporan = $totalRow['total'];
$totalPages = ceil($totalLaporan / $limit);

// Query database untuk mendapatkan jumlah laporan selesai dan diproses
$totalSelesaiQuery = "SELECT COUNT(*) as total FROM laporan WHERE ID_STATUS = (SELECT ID_STATUS FROM status_laporan WHERE STATUS_LAPORAN = 'Selesai')";
$totalSelesaiResult = $conn->query($totalSelesaiQuery);
$totalSelesaiRow = $totalSelesaiResult->fetch_assoc();
$totalLaporanSelesai = $totalSelesaiRow['total'];
$totalPagesSelesai = ceil($totalLaporanSelesai / $limit);

$totalDiprosesQuery = "SELECT COUNT(*) as total FROM laporan WHERE ID_STATUS = (SELECT ID_STATUS FROM status_laporan WHERE STATUS_LAPORAN = 'Diproses')";
$totalDiprosesResult = $conn->query($totalDiprosesQuery);
$totalDiprosesRow = $totalDiprosesResult->fetch_assoc();
$totalLaporanDiproses = $totalDiprosesRow['total'];
$totalPagesDiproses = ceil($totalLaporanDiproses / $limit);

// Query database untuk mendapatkan jumlah laporan masuk
$totalMasukQuery = "SELECT COUNT(*) as total FROM laporan WHERE ID_STATUS = (SELECT ID_STATUS FROM status_laporan WHERE STATUS_LAPORAN = 'Terkirim')";
$totalMasukResult = $conn->query($totalMasukQuery);
$totalMasukRow = $totalMasukResult->fetch_assoc();
$totalLaporanMasuk = $totalMasukRow['total'];
$totalPagesMasuk = ceil($totalLaporanMasuk / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Laporan - Sistem Informasi Pelaporan Infrastruktur</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php include 'components/sidebar_admin.php'; ?>

    <main class="main-content">

        <?php if (isset($_GET['message']) && isset($_GET['type'])): ?>
            <div class="<?php echo $_GET['type'] == 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <div class="page-title-container">
            <h2 class="page-title">Daftar Laporan Terbuat</h2>
            <p class="page-description">Berikut ini adalah daftar seluruh laporan yang pernah dibuat.</p>
        </div>

        <div class="tab-container">
            <div class="tab active" data-tab="all">Semua Laporan</div>
            <!-- <div class="tab" data-tab="masuk">Laporan Masuk</div> -->
            <div class="tab" data-tab="diproses">Laporan Diproses</div>
            <div class="tab" data-tab="selesai">Laporan Selesai</div>
            <div class="searchbar-container">
                <input type="text" class="searchbar" id="searchbar" placeholder="Cari laporan...">
                <div class="searchbar-icon">
                    <img src="assets/search.png" alt="Search Icon">
                </div>
            </div>
        </div>

        <?php

        // Query utk ndapatkan data laporan dengan pagination untuk Semua Laporan
        $sql = "SELECT laporan.*, warga.NAMA_WARGA, kategori_kejadian.KATEGORI_KEJADIAN, status_laporan.STATUS_LAPORAN 
FROM laporan 
JOIN warga ON laporan.NIK = warga.NIK
JOIN kategori_kejadian ON laporan.ID_KATEGORI = kategori_kejadian.ID_KATEGORI
JOIN status_laporan ON laporan.ID_STATUS = status_laporan.ID_STATUS
ORDER BY laporan.TANGGAL_SUBMIT DESC
LIMIT $limit OFFSET $offset";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusClass = '';
                if ($row["STATUS_LAPORAN"] == 'Selesai') {
                    $statusClass = 'selesai';
                } elseif ($row["STATUS_LAPORAN"] == 'Diproses') {
                    $statusClass = 'diproses';
                }
                echo "<div class='laporan $statusClass active'>";
                echo "<div class='laporan-header'>";
                echo "<h3 class='nama-warga'>" . htmlspecialchars($row["NAMA_WARGA"]) . "</h3>";
                echo "<p class='tanggal-submit'>" . htmlspecialchars($row["TANGGAL_SUBMIT"]) . "</p>";
                echo "</div>";
                echo "<div class='laporan-details'>";
                echo "<div class='tanggal-icon'><img src='assets/calendar.png' alt='Tanggal' /></div>";
                echo "<p class='tanggal-kejadian'>" . htmlspecialchars($row["TANGGAL_KEJADIAN"]) . "</p>";
                echo "<div class='kategori-icon'><img src='assets/category.png' alt='Kategori' /></div>";
                echo "<p class='kategori-kejadian'>" . htmlspecialchars($row["KATEGORI_KEJADIAN"]) . "</p>";
                echo "<div class='tempat-icon'><img src='assets/location.png' alt='Tempat' /></div>";
                echo "<p class='tempat-kejadian'>" . htmlspecialchars($row["LOKASI_KEJADIAN"]) . "</p>";
                echo "<div class='status-icon'><img src='assets/status.png' alt='Status' /></div>";
                echo "<select class='status-dropdown' data-id='" . $row['ID_LAPORAN'] . "'>";

                // Query tuk ambil status laporan dari database
                $statusQuery = "SELECT * FROM status_laporan";
                $statusResult = $conn->query($statusQuery);

                if ($statusResult->num_rows > 0) {
                    while ($statusRow = $statusResult->fetch_assoc()) {
                        $selected = ($statusRow['ID_STATUS'] == $row['ID_STATUS']) ? 'selected' : '';
                        echo "<option value='{$statusRow['ID_STATUS']}' $selected>{$statusRow['STATUS_LAPORAN']}</option>";
                    }
                }
                echo "</select>";
                echo "</div>";
        
                echo "<h2 class='judul-laporan'>" . htmlspecialchars($row["JUDUL_LAPORAN"]) . "</h2>";
                echo "<p class='isi-laporan'>" . htmlspecialchars($row["ISI_LAPORAN"]) . "</p>";
                if ($row["LAMPIRAN"]) {
                    $imgSrc = 'data:image/jpeg;base64,' . base64_encode($row["LAMPIRAN"]);
                    echo "<div class='laporan-image-container'>";
                    echo "<img class='laporan-image' src='$imgSrc' alt='Lampiran' data-src='$imgSrc' />";
                    echo "</div>";
                }
                echo "</div>"; 
            }
        } else {
            echo "<p class='no-laporan'>Tidak ada laporan.</p>";
        }
        ?>

        <!-- Pagination buttons -->
        <div class="pagination" data-tab="all">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn">Halaman Sebelumnya</a>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn">Halaman Selanjutnya</a>
            <?php endif; ?>
        </div>

        <div class="pagination selesai-pagination" data-tab="selesai" style="display:none;">
            <!-- Logic for Selesai Pagination -->
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&tab=selesai" class="pagination-btn">Halaman Sebelumnya</a>
            <?php endif; ?>

            <?php if ($page < $totalPagesSelesai): ?>
                <a href="?page=<?php echo $page + 1; ?>&tab=selesai" class="pagination-btn">Halaman Selanjutnya</a>
            <?php endif; ?>
        </div>

        <div class="pagination diproses-pagination" data-tab="diproses" style="display:none;">
            <!-- Logic for Diproses Pagination -->
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&tab=diproses" class="pagination-btn">Halaman Sebelumnya</a>
            <?php endif; ?>

            <?php if ($page < $totalPagesDiproses): ?>
                <a href="?page=<?php echo $page + 1; ?>&tab=diproses" class="pagination-btn">Halaman Selanjutnya</a>
            <?php endif; ?>
        </div>

        <div class="pagination masuk-pagination" data-tab="masuk" style="display:none;">
            <!-- Logic for Masuk Pagination -->
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&tab=masuk" class="pagination-btn">Halaman Sebelumnya</a>
            <?php endif; ?>

            <?php if ($page < $totalPagesMasuk): ?>
                <a href="?page=<?php echo $page + 1; ?>&tab=masuk" class="pagination-btn">Halaman Selanjutnya</a>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <div class="overlay" id="overlay">
        <button class="close-btn" id="closeBtn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path fill="white"
                    d="M19.07,4.93a1,1,0,0,0-1.41,0L12,10.59,6.34,4.93A1,1,0,1,0,4.93,6.34L10.59,12l-5.66,5.66a1,1,0,0,0,1.41,1.41L12,13.41l5.66,5.66a1,1,0,1,0,1.41-1.41L13.41,12l5.66-5.66A1,1,0,0,0,19.07,4.93Z" />
            </svg>
        </button>
        <a class="download-btn" id="downloadBtn" download>Download</a>
        <img id="overlayImage" src="" alt="Lampiran Besar" />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.getElementById('overlay');
            const overlayImage = document.getElementById('overlayImage');
            const closeBtn = document.getElementById('closeBtn');
            const downloadBtn = document.getElementById('downloadBtn');

            document.querySelectorAll('.laporan-image').forEach(function (image) {
                image.addEventListener('click', function () {
                    const imgSrc = this.getAttribute('data-src');
                    overlayImage.src = imgSrc;
                    downloadBtn.href = imgSrc;
                    overlay.style.display = 'flex';
                });
            });

            closeBtn.addEventListener('click', function () {
                overlay.style.display = 'none';
            });

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) {
                    overlay.style.display = 'none';
                }
            });

            // Tab functionality
            const tabs = document.querySelectorAll('.tab');
            const laporanElements = document.querySelectorAll('.laporan');
            const paginationContainers = document.querySelectorAll('.pagination');

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    tabs.forEach(function (t) {
                        t.classList.remove('active');
                    });
                    this.classList.add('active');

                    const tabCategory = this.getAttribute('data-tab');

                    laporanElements.forEach(function (laporan) {
                        if (tabCategory === 'all') {
                            laporan.classList.add('active');
                        } else {
                            if (laporan.classList.contains(tabCategory)) {
                                laporan.classList.add('active');
                            } else {
                                laporan.classList.remove('active');
                            }
                        }
                    });

                    // Show relevant pagination
                    paginationContainers.forEach(function (pagination) {
                        if (pagination.getAttribute('data-tab') === tabCategory) {
                            pagination.style.display = 'flex';
                        } else {
                            pagination.style.display = 'none';
                        }
                    });
                });
            });

            // Search functionality
            const searchbar = document.getElementById('searchbar');
            searchbar.addEventListener('input', function () {
                const searchQuery = this.value.toLowerCase();

                laporanElements.forEach(function (laporan) {
                    const laporanText = laporan.innerText.toLowerCase();
                    if (laporanText.includes(searchQuery)) {
                        laporan.classList.add('active');
                    } else {
                        laporan.classList.remove('active');
                    }
                });
            });

            // Set default tab to "Semua Laporan"
            document.querySelector('.tab[data-tab="all"]').click();
        });

        // Event listener untuk dropdown status
        document.querySelectorAll('.status-dropdown').forEach(function (select) {
            select.addEventListener('change', function () {
                const newStatusId = this.value;
                const laporanId = this.getAttribute('data-id');

                // Kirim AJAX request untuk update status
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Status berhasil diperbarui.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal memperbarui status.'
                            });
                        }
                    } else {
                        console.error('Failed to update status.');
                    }
                };
                xhr.send('laporan_id=' + laporanId + '&status_id=' + newStatusId);
            });
        });
    </script>
</body>

</html>