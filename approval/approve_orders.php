<?php

session_start();

// Check if the user is logged in and has the approver role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'approver') {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

// Fetch list of pending orders
$order_query = "SELECT * FROM orders WHERE approval_status = 'pending'";
$order_result = $conn->query($order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Approve Orders</title>

</head>
<body>
    <div class="container">
    <h2>Approve Orders</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Vehicle ID</th>
                <th>Driver ID</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Action</th>
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
                echo "<td><a href='approve_order_process.php?order_id={$row['order_id']}&action=approve'>Approve</a> | <a href='approve_order_process.php?order_id={$row['order_id']}&action=reject'>Reject</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
</body>
</html>
