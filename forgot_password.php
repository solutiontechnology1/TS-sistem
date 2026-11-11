<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Mileage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Reset Password</h2>
                <p>Masukkan maklumat untuk masuk</p>
            </div>

            <form method="post" action="process_reset_password.php">
                <div class="input-wrapper">
                    <!-- Username -->
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="text" id="username" name="username" required autocomplete="username">
                            <label for="username">Masukkan Username</label>
                        </div>
                        <span class="error-message" id="usernameError"></span>
                    </div>

                    <!-- Kata Laluan Baru -->
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="password" id="new_password" name="new_password" placeholder="Kata Laluan Baru" required autocomplete="current-password">
                            <label for="new_password">Password</label>
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                                <span class="toggle-icon"></span>
                            </button>
                        </div>
                        <span class="error-message" id="passwordError"></span>
                    </div>

                    <!-- Sahkan Kata Laluan -->
                    <div class="form-group">
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Sahkan Kata Laluan" required autocomplete="current-password">
                            <label for="confirm_password">Sahkan Password</label>
                            <button type="button" class="password-toggle" id="passwordToggleConfirm" aria-label="Toggle password visibility">
                                <span class="toggle-icon"></span>
                            </button>
                        </div>
                        <span class="error-message" id="confirmpasswordError"></span>
                    </div>
                </div>

                <!-- Butang Reset dan Batal -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Reset Password</button>
                    <a href="login.php" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../shared/js/form-utils.js"></script>
    <script src="script.js"></script>
</body>
</html>
