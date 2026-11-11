<?php
session_start();
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Semak username
    $stmt = $conn->prepare("SELECT id, username, password, nama, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika user wujud
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Semak password
        if (password_verify($password, $user['password'])) {
            // Simpan session
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            // Redirect ke index
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak dijumpai!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk Mileage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Log Masuk</h2>
                <p>Masukkan maklumat untuk masuk</p>
            </div>
    <form method="post">
        <div class="input-wrapper">
            <div class="form-group">
            <div class="input-wrapper">
                <input type="text" id="username" name="username" required autocomplete="username">
                <label for="text">Username</label>
            </div>
            <span class="error-message" id="usernameError"></span>
        </div>

       <div class="form-group">
        <div class="input-wrapper">
            <input type="password" id="password" name="password" required autocomplete="current-password">
            <label for="password">Password</label>
            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                <span class="toggle-icon"></span>
            </button>
        </div>
        <span class="error-message" id="passwordError"></span>
    </div>

         <button type="submit" class="login-btn">
                    <span class="btn-text">Log Masuk</span>
                    <span class="btn-loader"></span>
                </button>
            </form>


        <p><a href="forgot_password.php">Lupa Password?</a></p>
    </div>
     <div class="success-message" id="successMessage">
                <div class="success-icon">✓</div>
                <h3>Welcome back!</h3>
                <p>Redirecting to your dashboard...</p>
            </div>
        </div>
    </div>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <script src="../../shared/js/form-utils.js"></script>
    <script src="script.js"></script>
</body>
</html>
