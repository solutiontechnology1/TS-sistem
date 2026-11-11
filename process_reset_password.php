<?php
session_start();
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi
    if ($new_password !== $confirm_password) {
        die("Kata laluan dan pengesahan kata laluan tidak sepadan! <a href='forgot_password.php'>Kembali</a>");
    }

    // Semak sama ada username wujud
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Username tidak wujud! <a href='forgot_password.php'>Kembali</a>");
    }

    // Hash password baru
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password dalam database
    $update = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $update->bind_param("ss", $hashed_password, $username);

    if ($update->execute()) {
        echo "Kata laluan berjaya ditukar! <a href='login.php'>Login di sini</a>";
    } else {
        echo "Ralat! Tidak dapat kemaskini kata laluan.";
    }

    $update->close();
    $stmt->close();
    $conn->close();
} else {
    header("Location: forgot_password.php");
    exit;
}
?>
