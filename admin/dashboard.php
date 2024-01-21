
<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

// Fetch list of vehicles
$vehicle_query = "SELECT * FROM vehicles";
$vehicle_result = $conn->query($vehicle_query);

// Fetch list of orders
$order_query = "SELECT * FROM orders";
$order_result = $conn->query($order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Admin Dashboard</title>

</head>
<body>
    <div class="container">
<h2>Welcome, Admin!</h2>

<!-- Display Vehicle List -->
<div id="vehicleList">
    <h3>Vehicle List</h3>
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
            while ($vehicle = $vehicle_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$vehicle['vehicle_id']}</td>";
                echo "<td>{$vehicle['type']}</td>";
                echo "<td>{$vehicle['location']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    <!-- Form Input Pemesanan -->
    <h3>Add Order</h3>
    <form id="orderForm" action="add_order_process.php" method="post">
        <label for="vehicle">Select Vehicle:</label>
        <select id="vehicle" name="vehicle" required>
            <?php
            // Fetch list of vehicles for the form
            $vehicle_result = $conn->query($vehicle_query);
            while ($vehicle = $vehicle_result->fetch_assoc()) {
                echo "<option value=\"{$vehicle['vehicle_id']}\">{$vehicle['type']} - {$vehicle['location']}</option>";
            }
            ?>
        </select>

        <label for="driver">Select Driver:</label>
        <select id="driver" name="driver" required>
            <?php
            // Fetch list of drivers for the form
            $driver_query = "SELECT * FROM drivers";
            $driver_result = $conn->query($driver_query);
            while ($driver = $driver_result->fetch_assoc()) {
                echo "<option value=\"{$driver['driver_id']}\">{$driver['name']}</option>";
            }
            ?>
        </select>

        <button type="submit">Submit Order</button>
    </form>

    <!-- Display Order List -->
    <h3>Order List</h3>
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

    <script>
        document.getElementById('orderForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('orderResult').innerHTML = xhr.responseText;
                }
            };

            xhr.open('POST', 'add_order_process.php', true);
            xhr.send(formData);
        });
    </script>


<!-- Tombol Logout -->
<a class="logout-button" href="../logout.php">Logout</a>
</div>
</body>
</html>
