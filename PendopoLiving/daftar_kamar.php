<?php
// Koneksi database
include('koneksi.php');

// Ambil parameter dari URL
$fasilitas = isset($_GET['fasilitas']) ? $_GET['fasilitas'] : '';
$idBlok = isset($_GET['blok']) ? $_GET['blok'] : '';

// Jika fasilitas belum dipilih, tampilkan pilihan fasilitas
if (empty($fasilitas)) {
    echo "<h2>Pilih Fasilitas Kamar</h2>";
    echo "<div class='blok-buttons'>";
    echo "<a href='daftar_kamar.php?fasilitas=kamar mandi dalam' class='blok-btn'>Kamar Mandi Dalam</a>";
    echo "<a href='daftar_kamar.php?fasilitas=kamar mandi luar' class='blok-btn'>Kamar Mandi Luar</a>";
    echo "</div>";
} elseif (empty($idBlok)) {
    // Jika fasilitas sudah dipilih tetapi blok belum, tampilkan pilihan blok
    echo "<h2>Pilih Blok Kost untuk Fasilitas $fasilitas</h2>";
    echo "<div class='blok-buttons'>";
    $blokOptions = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
    foreach ($blokOptions as $blok) {
        echo "<a href='daftar_kamar.php?fasilitas=$fasilitas&blok=$blok' class='blok-btn'>Blok $blok</a>";
    }
    echo "</div>";
} else {
    // Jika fasilitas dan blok sudah dipilih, tampilkan daftar kamar yang sesuai
    $sql = "SELECT k.idKamar, k.namaKamar, f.namaFasilitas, f.biayaTambahan, b.namaBlok, k.harga, k.status
            FROM kamar k
            JOIN kamar_fasilitas kf ON k.idKamar = kf.idKamar
            JOIN fasilitas f ON kf.idFasilitas = f.idFasilitas
            JOIN blok b ON k.idBlok = b.idBlok
            WHERE f.namaFasilitas = '$fasilitas'
            AND b.namaBlok = '$idBlok'
            ORDER BY k.idKamar";
    
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Daftar Kamar di Blok $idBlok dengan Fasilitas $fasilitas</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='kamar-item'>";
            echo "<h4>" . $row['namaKamar'] . "</h4>";
            echo "<p>Fasilitas: " . $row['namaFasilitas'] . "</p>";
            echo "<p>Biaya Tambahan: Rp. " . number_format($row['biayaTambahan'], 0, ',', '.') . "</p>";
            echo "<p>Harga: Rp. " . number_format($row['harga'], 0, ',', '.') . "</p>";
            echo "<p>Status: " . $row['status'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "Tidak ada kamar dengan fasilitas $fasilitas di blok $idBlok.";
    }
}
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

        /* Styling untuk kamar item */
        .kamar-item {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .kamar-item h4 {
            margin: 0;
            color: #4CAF50;
            font-size: 24px;
        }

        .kamar-item p {
            margin: 5px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>

</body>
</html>
