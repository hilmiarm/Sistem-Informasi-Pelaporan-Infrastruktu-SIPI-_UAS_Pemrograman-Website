<?php
include 'config.php'; 

// Hash password
$newPassword = password_hash('123', PASSWORD_DEFAULT);

$sql = "UPDATE admin SET PASSWORD='$newPassword' WHERE USERNAME='admin'";
if ($conn->query($sql) === TRUE) {
    echo "Password updated successfully";
} else {
    echo "Error updating password: " . $conn->error;
}

$conn->close();
?>
