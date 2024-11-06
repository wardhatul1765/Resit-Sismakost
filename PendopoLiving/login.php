<?php
<<<<<<< HEAD
   $koneksi = new mysqli("localhost", "root", "", "pendopo_living");
=======
session_start();
require 'koneksi.php';  // Menggunakan koneksi yang sudah ada

$message = ''; // Variabel untuk menyimpan pesan

// Tampilkan pesan dari session jika ada
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Query untuk mencari user berdasarkan email
    $sql = $koneksi->query("SELECT * FROM penyewa WHERE email='$email'");
    $data = $sql->fetch_assoc();
    
    if ($data) {
        // Memeriksa password yang di-hash
        if (password_verify($password, $data['password'])) {
            // Simpan ID penyewa ke dalam session
            $_SESSION['penyewa_id'] = $data['id']; // Sesuaikan jika kolom ID di penyewa adalah 'id'
            $_SESSION['user_name'] = $data['nama_depan']; // Simpan nama depan jika ingin menampilkan nama pengguna

            // Redirect ke halaman utama atau halaman yang sesuai setelah login
            header("location:index.php");
            exit(); // Pastikan untuk menghentikan eksekusi script setelah redirect
        } else {
            $message = "Password salah.";
        }
    } else {
        $message = "Email tidak ditemukan.";
    }
}
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
?>

<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>login</title>
  <!-- link css -->
  <link rel="stylesheet" href="style.css"> 
  <!--  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
  <!-- ini merupakan link box icon -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>


    <div class="wrapper">
    <!-- <a href="index.php" class="btn">Kembali</a> -->
        <form method="post">
          <h1>Login</h1>

          <div class="input-box">
            <input type="text" placeholder="email" name="email" required>
            <i class='bx bxs-user'></i>
          </div>

          <div class="input-box">
            <input type="password" placeholder="Password" name="password" required>
            <i class='bx bxs-lock-alt' ></i>
          </div>
          
          <button type="submit" class="btn" name="login">Login</button>

          <div class="register-link">
            <p>tidak punya akun? <a href="daftar.php">Daftar</a></p>
            <br>
            <p><a href="index.php">Kembali ke beranda</a></p>
          </div>
          

          </div>
        </form>
      </div>

</body>
</html>

<?php

   if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    $sql = $koneksi->query("SELECT * FROM admin where email='$email' and password='$password'");

    if ($sql->num_rows > 0) {
      $data = $sql->fetch_assoc();
  } else {
      $data = null; // Atur $data sebagai null jika tidak ada hasil
  }

    $ketemu = $sql->num_rows;

    if ($ketemu >= 1) {

      session_start();

      if ($data['level'] == "admin") {
        $_SESSION['admin'] = $data['id'];

        header("location:dashboard.php");
      }elseif ($data['level'] == "petugas") {
        $_SESSION['petugas'] = $data['id'];

        header("location:dashboard.php");
      }
    }else {
      ?>
        <script type="text/javascript">
                    alert("Gagal");
                    window.location.href="login.php";
                 </script>
      <?php

    }

   }
?>
=======
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

            <button type="submit" class="btn" name="login">Login</button>

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
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
