<?php

session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

// Fetch list of vehicles
$query = "SELECT * FROM vehicles";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle List</title>

</head>
<body>
    <h2>Vehicle List</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Vehicle ID</th>
                <th>Type</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['vehicle_id']}</td>";
                echo "<td>{$row['type']}</td>";
                echo "<td>{$row['location']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
