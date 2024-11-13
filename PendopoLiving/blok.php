<?php
// Koneksi database
include('koneksi.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Blok</title>
    <style>
        /* Resetting some default styles */
        body, h2 {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9; /* Soft background color */
            color: #333; /* Dark text for better readability */
            line-height: 1.6;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4CAF50; /* Green color for heading */
            font-size: 30px;
            font-weight: bold;
        }

        .blok-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Grid layout with responsive columns */
            gap: 20px;
            justify-items: center; /* Center the buttons */
            margin-top: 20px;
        }

        .blok-btn {
            padding: 15px 25px;
            font-size: 18px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Add shadow for better depth */
            border: none;
        }

        .blok-btn:hover {
            background-color: #45a049;
            transform: scale(1.05); /* Slightly enlarge the button on hover */
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }

        /* Add a subtle background and padding for better visual separation */
        .blok-buttons {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Optional: Add a border to buttons for further enhancement */
        .blok-btn {
            border: 2px solid #4CAF50;
            transition: background-color 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
        }

        .blok-btn:hover {
            border-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Pilih Blok Kost</h2>

    <!-- Pilihan Blok dengan Tombol -->
    <div class="blok-buttons">
        <a href="daftar_kamar.php?blok=A" class="blok-btn">Blok A</a>
        <a href="daftar_kamar.php?blok=B" class="blok-btn">Blok B</a>
        <a href="daftar_kamar.php?blok=C" class="blok-btn">Blok C</a>
        <a href="daftar_kamar.php?blok=D" class="blok-btn">Blok D</a>
        <a href="daftar_kamar.php?blok=E" class="blok-btn">Blok E</a>
        <a href="daftar_kamar.php?blok=F" class="blok-btn">Blok F</a>
        <a href="daftar_kamar.php?blok=G" class="blok-btn">Blok G</a>
        <a href="daftar_kamar.php?blok=H" class="blok-btn">Blok H</a>
        <a href="daftar_kamar.php?blok=I" class="blok-btn">Blok I</a>
    </div>
</body>
</html>
