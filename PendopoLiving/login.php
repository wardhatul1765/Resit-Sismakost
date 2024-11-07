<?php
session_start();
include 'koneksi.php'; // Sesuaikan dengan file koneksi Anda

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Proses login untuk admin
    if ($user_type === 'admin') {
        $query = "SELECT * FROM admin WHERE Email = ? AND Password = ?"; 
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc(); // Ambil data pengguna
            $_SESSION['user_type'] = 'admin';
            $_SESSION['email'] = $email;
            $_SESSION['namaAdmin'] = $user['namaAdmin'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Login Admin gagal!";
        }
    }

    // Proses login untuk penyewa
    elseif ($user_type === 'penyewa') {
        $query = "SELECT * FROM penyewa WHERE email = ?"; 
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_type'] = 'penyewa';
                $_SESSION['email'] = $email;
                $_SESSION['namaPenyewa'] = $user['namaPenyewa'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Login Penyewa gagal! Password salah.";
            }
        } else {
            $error = "Login Penyewa gagal! Email tidak ditemukan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div id="roleWrapper">
    <div id="roleContainer">
        <h2 id="roleTitle">Lanjutkan Aktivitas sebagai</h2>

        <div id="roleButtons">
            <div class="role-button-container">
                <div class="role-content">
                    <img src="https://static-asset.papikost.com/images/general/pencari-kost.svg" alt="Pencari Kost" width="100">
                    <div class="role-info">
                        <h3>Pencari Kost</h3>
                        <p>Cari tempat kost yang nyaman dan sesuai dengan kebutuhan Anda.</p>
                        <button onclick="selectUserType('penyewa')">Pencari Kost</button>
                    </div>
                </div>
            </div>
            <div class="role-button-container">
                <div class="role-content">
                    <img src="https://static-asset.papikost.com/images/general/pemilik-kost.svg" alt="Pemilik Kost" width="100">
                    <div class="role-info">
                        <h3>Pemilik Kost</h3>
                        <p>Kelola kost Anda dan temukan penyewa yang sesuai.</p>
                        <button onclick="selectUserType('admin')">Pemilik Kost</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Form, initially hidden -->
    <form id="loginForm" method="post" action="" style="display: none;">
        <input type="hidden" name="user_type" id="userType">
        
        <div class="input-container">
            <label>Email:</label>
            <input type="text" name="email" required>
        </div>
        
        <div class="input-container">
            <label>Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        
        <div class="show-password-container">
            <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> <label for="showPassword">Tampilkan Password</label>
        </div>
        
        <button type="submit">Login</button>

        <div class="links">
            <!-- Hanya tampilkan Daftar untuk penyewa -->
            <a href="register.php" style="display: none;" id="daftarLink">Daftar</a>
            <a href="lupa_password.php">Lupa Password?</a>
        </div>
    </form>
</div>

<?php
if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

<script>
function selectUserType(type) {
    // Set the user type value in the hidden input field
    document.getElementById('userType').value = type;

    // Hide the role selection and display the login form
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('roleButtons').style.display = 'none';
    document.getElementById('roleTitle').style.display = 'none';

    // Tampilkan link Daftar hanya untuk penyewa
    if (type === 'penyewa') {
        document.getElementById('daftarLink').style.display = 'block'; // Tampilkan tombol Daftar
    } else {
        document.getElementById('daftarLink').style.display = 'none'; // Sembunyikan tombol Daftar untuk admin
    }
}

function togglePasswordVisibility() {
    var passwordField = document.getElementById('password');
    var showPasswordCheckbox = document.getElementById('showPassword');
    passwordField.type = showPasswordCheckbox.checked ? 'text' : 'password';
}
</script>

</body>
</html>
