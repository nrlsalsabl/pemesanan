<?php
// login_process.php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Set session variables
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        // Redirect to dashboard based on role
        if ($user['role'] == 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($user['role'] == 'approver') {
            header('Location: approval/dashboard.php');
        }
    } else {
        // Redirect back to login with an error message
        header('Location: login.php?error=1');
    }
}
?>
