<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Pelaporan Infrastruktur</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include 'components/sidebar_admin.php'; ?>

    <main class="main-content">
        <div class="page-content">
            <?php
            include 'config.php';

            // Get total number of reports
            $total_reports_query = "SELECT COUNT(*) AS total FROM laporan";
            $total_reports_result = $conn->query($total_reports_query);
            $total_reports = $total_reports_result->fetch_assoc()['total'];

            // Get number of reports by status
            $status_reports_query = "SELECT status_laporan.STATUS_LAPORAN, COUNT(*) AS total FROM laporan 
            RIGHT JOIN status_laporan ON laporan.ID_STATUS = status_laporan.ID_STATUS 
            GROUP BY status_laporan.STATUS_LAPORAN 
            ORDER BY FIELD(status_laporan.STATUS_LAPORAN, 'terkirim', 'terverifikasi', 'ditolak', 'diproses', 'selesai')";

            $status_reports_result = $conn->query($status_reports_query);
            $status_reports = [];
            while ($row = $status_reports_result->fetch_assoc()) {
                $status_reports[$row['STATUS_LAPORAN']] = $row['total'];
            }

            // Get number of reports by category
            $category_reports_query = "SELECT kategori_kejadian.KATEGORI_KEJADIAN, COUNT(*) AS total FROM laporan 
                                       JOIN kategori_kejadian ON laporan.ID_KATEGORI = kategori_kejadian.ID_KATEGORI 
                                       GROUP BY laporan.ID_KATEGORI";
            $category_reports_result = $conn->query($category_reports_query);
            $category_reports = [];
            while ($row = $category_reports_result->fetch_assoc()) {
                $category_reports[$row['KATEGORI_KEJADIAN']] = $row['total'];
            }
            ?>

            <div class="admin-page-title-container">
                <h2 class="admin-page-title">Jumlah Laporan Keseluruhan</h2>
                <p class="admin-page-description"><?php echo $total_reports; ?> Laporan</p>
            </div>

            <div class="chart-container">
                <div class="chart-title">
                    <h2 class="page-title">Diagram Laporan berdasarkan Status Laporan</h2>
                </div>
                <div class="charts">
                    <div class="bar-chart-container">
                        <canvas id="statusBarChart"></canvas>
                    </div>
                    <div class="pie-chart-container">
                        <canvas id="statusPieChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-title">
                    <h2 class="page-title">Diagram Laporan berdasarkan Kategori Laporan</h2>
                </div>
                <div class="charts">
                    <div class="bar-chart-container">
                        <canvas id="categoryBarChart"></canvas>
                    </div>
                    <div class="pie-chart-container">
                        <canvas id="categoryPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <script>
        // Data for Status Charts
        const statusLabels = <?php echo json_encode(array_keys($status_reports)); ?>;
        const statusData = <?php echo json_encode(array_values($status_reports)); ?>;

        // Data for Category Charts
        const categoryLabels = <?php echo json_encode(array_keys($category_reports)); ?>;
        const categoryData = <?php echo json_encode(array_values($category_reports)); ?>;

        // Config for Status Pie Chart
        const statusPieConfig = {
            type: 'pie',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            },
            options: {
                responsive: true
            }
        };

        // Config for Category Pie Chart
        const categoryPieConfig = {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FFCD56', '#4D5360', '#F7464A', '#46BFBD', '#FDB45C']
                }]
            },
            options: {
                responsive: true
            }
        };

        // Config for Status Bar Chart
        const statusBarConfig = {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: statusData,
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Config for Category Bar Chart
        const categoryBarConfig = {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: categoryData,
                    backgroundColor: '#FF6384'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Render Status Pie Chart
        const statusPieCtx = document.getElementById('statusPieChart').getContext('2d');
        new Chart(statusPieCtx, statusPieConfig);

        // Render Category Pie Chart
        const categoryPieCtx = document.getElementById('categoryPieChart').getContext('2d');
        new Chart(categoryPieCtx, categoryPieConfig);

        // Render Status Bar Chart
        const statusBarCtx = document.getElementById('statusBarChart').getContext('2d');
        new Chart(statusBarCtx, statusBarConfig);

        // Render Category Bar Chart
        const categoryBarCtx = document.getElementById('categoryBarChart').getContext('2d');
        new Chart(categoryBarCtx, categoryBarConfig);
    </script>
</body>

</html>