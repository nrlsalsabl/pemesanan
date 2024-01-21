<!-- logout.php -->
<?php
session_start();

// Hapus semua variabel sesi
session_unset();

// Hancurkan semua data sesi
session_destroy();

// Redirect ke halaman login
header('Location: login.php');
exit();
?>
