
<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

// Fetch list of vehicles and drivers for the form
$vehicle_query = "SELECT * FROM vehicles";
$vehicle_result = $conn->query($vehicle_query);

$driver_query = "SELECT * FROM drivers";
$driver_result = $conn->query($driver_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Add Order</title>

</head>
<body>
    <div class="container">
    <h2>Add Order</h2>

    <form id="orderForm" action="add_order_process.php" method="post">
        <label for="vehicle">Select Vehicle:</label>
        <select id="vehicle" name="vehicle" required>
            <?php
            while ($vehicle = $vehicle_result->fetch_assoc()) {
                echo "<option value=\"{$vehicle['vehicle_id']}\">{$vehicle['type']} - {$vehicle['location']}</option>";
            }
            ?>
        </select>

        <label for="driver">Select Driver:</label>
        <select id="driver" name="driver" required>
            <?php
            while ($driver = $driver_result->fetch_assoc()) {
                echo "<option value=\"{$driver['driver_id']}\">{$driver['name']}</option>";
            }
            ?>
        </select>

        <button type="submit">Submit Order</button>
    </form>
    
    <div id="orderResult">

    </div>

<script>
    document.getElementById('orderForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);

                // Update the order list
                document.getElementById('orderResult').innerHTML = response.orderList;

                // Update the vehicle list
                document.getElementById('vehicleList').innerHTML = response.vehicleList;
            }
        };

        xhr.open('POST', 'add_order_process.php', true);
        xhr.send(formData);
    });
</script>
</div>
</body>
</html>
