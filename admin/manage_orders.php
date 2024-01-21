<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

// Fetch list of orders
$order_query = "SELECT * FROM orders";
$order_result = $conn->query($order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>

</head>
<body>
    <h2>Manage Orders</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Vehicle ID</th>
                <th>Driver ID</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $order_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['order_id']}</td>";
                echo "<td>{$row['vehicle_id']}</td>";
                echo "<td>{$row['driver_id']}</td>";
                echo "<td>{$row['approval_status']}</td>";
                echo "<td>{$row['order_date']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
