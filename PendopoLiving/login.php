<?php
session_start();
require 'koneksi.php';

$message = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Query untuk mencari user berdasarkan email
    $sql = $koneksi->query("SELECT * FROM penyewa WHERE email='$email'");
    $data = $sql->fetch_assoc();
    
    if ($data) {
        if (password_verify($password, $data['password'])) {
            $_SESSION['penyewa_id'] = $data['id'];
            $_SESSION['user_name'] = $data['nama_depan'];

            // Redirect ke halaman dashboard setelah login
            header("location:dashboard.php");
            exit();
        } else {
            $message = "Password salah.";
        }
    } else {
        $message = "Email tidak ditemukan.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var passwordToggle = document.getElementById("togglePassword");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordToggle.classList.remove('fa-eye-slash');
                passwordToggle.classList.add('fa-eye');
            } else {
                passwordInput.type = "password";
                passwordToggle.classList.remove('fa-eye');
                passwordToggle.classList.add('fa-eye-slash');
            }
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <form method="post">
            <h1>Login</h1>

            <div class="input-box">
                <input type="text" placeholder="Email" name="email" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Password" name="password" id="password" required>
                <span onclick="togglePassword()">
                    <i id="togglePassword" class="fas fa-eye-slash"></i>
                </span>
            </div>

            <button type="submit" class="btn" name="login">login</button>
            
            <div class="register-link">
                <p>Tidak punya akun? <a href="register.php">Daftar</a></p>
                <p>Lupa password? <a href="lupa_password.php">Klik disini</a></p>
            </div>

            <?php if ($message): ?>
                <p style="color: red;"><?php echo $message; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>