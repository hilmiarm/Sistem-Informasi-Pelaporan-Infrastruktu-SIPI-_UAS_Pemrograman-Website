<?php
session_start();
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query the database for the user
    $sql = "SELECT * FROM admin WHERE USERNAME = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['PASSWORD'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['admin_id'] = $row['ID_ADMIN'];

            header("Location: dashboard_admin.php?message=Login successful&type=success");
            exit();
        } else {
            header("Location: login_admin.php?message=Incorrect password&type=error");
            exit();
        }
    } else {

        header("Location: login_admin.php?message=Username not found&type=error");
        exit();
    }
}
?>
