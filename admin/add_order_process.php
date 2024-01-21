<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit(); 
}

include '../config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle'];
    $driver_id = $_POST['driver'];

    // Insert order into the database
    $insert_query = "INSERT INTO orders (user_id, vehicle_id, driver_id) VALUES ({$_SESSION['user_id']}, $vehicle_id, $driver_id)";
    $conn->query($insert_query);

    // Fetch updated list of orders
    $order_query = "SELECT * FROM orders";
    $order_result = $conn->query($order_query);

    // Fetch list of vehicles
    $vehicle_query = "SELECT * FROM vehicles";
    $vehicle_result = $conn->query($vehicle_query);

    // Display the updated list of orders and vehicles
    echo json_encode([
        'orderList' => fetchOrdersHTML($order_result),
        'vehicleList' => fetchVehiclesHTML($vehicle_result)
    ]);
}

function fetchOrdersHTML($orderResult) {
    $html = "<table border='1'>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Vehicle ID</th>
                        <th>Driver ID</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>";

    while ($row = $orderResult->fetch_assoc()) {
        $html .= "<tr>";
        $html .= "<td>{$row['order_id']}</td>";
        $html .= "<td>{$row['vehicle_id']}</td>";
        $html .= "<td>{$row['driver_id']}</td>";
        $html .= "<td>{$row['approval_status']}</td>";
        $html .= "<td>{$row['order_date']}</td>";
        $html .= "</tr>";
    }

    $html .= "</tbody></table>";

    return $html;
}

function fetchVehiclesHTML($vehicleResult) {
    $html = "<h3>Vehicle List</h3>
            <table border='1'>
                <thead>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>Type</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>";

    while ($vehicle = $vehicleResult->fetch_assoc()) {
        $html .= "<tr>";
        $html .= "<td>{$vehicle['vehicle_id']}</td>";
        $html .= "<td>{$vehicle['type']}</td>";
        $html .= "<td>{$vehicle['location']}</td>";
        $html .= "</tr>";
    }

    $html .= "</tbody></table>";

    return $html;
}
?>
