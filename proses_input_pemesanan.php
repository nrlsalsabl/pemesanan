

<?php

include('config.php');
session_start();

// Periksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Proses formulir pemesanan di sini
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $jenis_kendaraan = $_POST['jenis_kendaraan'];
    $driver_id = $_POST['driver'];
    $atasan_id = $_POST['atasan'];

    $conn = mysqli_connect("host", "username", "password", "nama_database");

    // Periksa koneksi
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $queryDriver = "SELECT nama FROM driver WHERE id = '$driver_id'";
    $resultDriver = mysqli_query($conn, $queryDriver);
    $rowDriver = mysqli_fetch_assoc($resultDriver);
    $namaDriver = $rowDriver['nama'];

    $queryAtasan = "SELECT nama FROM atasan WHERE id = '$atasan_id'";
    $resultAtasan = mysqli_query($conn, $queryAtasan);
    $rowAtasan = mysqli_fetch_assoc($resultAtasan);
    $namaAtasan = $rowAtasan['nama'];

    // Lakukan penyimpanan data ke database
    $sql = "INSERT INTO pemesanan_kendaraan (jenis_kendaraan, driver, atasan) VALUES ('$jenis_kendaraan', '$namaDriver', '$namaAtasan')";

    if (mysqli_query($conn, $sql)) {
        // Setelah berhasil, arahkan kembali ke halaman dashboard
        header('Location: dashboard_admin.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>
