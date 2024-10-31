<?php
   $koneksi = new mysqli("localhost", "root", "", "pendopo_living");
?>

<!DOCTYPE html>
<html lang="en">
<head>
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