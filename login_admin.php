<!-- login_admin.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Informasi Pelaporan Infrastruktur</title>
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

        <h2 class="page-title">Login Admin</h2>
        <form action="process_login.php" method="post" class="login-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <div class="container">
                <input type="submit" value="Login" class="submit-button">
            </div>
        </form>
    </main>

    <?php include 'components/footer.php'; ?>

</body>
</html>
