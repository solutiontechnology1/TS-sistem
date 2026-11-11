<?php
session_start();
session_unset();
session_destroy();

if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $message = "Sesi anda telah tamat kerana tiada aktiviti selama 5 minit.";
} else {
    $message = "Anda telah berjaya log keluar.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout Mileage</title>
</head>
<body>
    <h3><?php echo $message; ?></h3>
    <a href="login.php">Login Semula</a>
</body>
</html>
