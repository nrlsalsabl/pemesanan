
<?php
session_start();

// Check if the user is logged in and has the approval role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'approver') {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

// Fetch list of pending orders
$pending_order_query = "SELECT * FROM orders WHERE approval_status = 'pending'";
$pending_order_result = $conn->query($pending_order_query);

// Fetch list of approved orders
$approved_order_query = "SELECT * FROM orders WHERE approval_status = 'approved'";
$approved_order_result = $conn->query($approved_order_query);

// Fetch list of rejected orders
$rejected_order_query = "SELECT * FROM orders WHERE approval_status = 'rejected'";
$rejected_order_result = $conn->query($rejected_order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Approval Dashboard</title>

    
    <script>
        function handleApproval(orderId, action) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);

                    // Update the respective order list
                    if (action === 'approve') {
                        document.getElementById('approvedOrderList').innerHTML = response.approvedOrderList;
                    } else if (action === 'reject') {
                        document.getElementById('rejectedOrderList').innerHTML = response.rejectedOrderList;
                    }
                }
            };

            xhr.open('GET', 'approve_order_process.php?order_id=' + orderId + '&action=' + action, true);
            xhr.send();
        }
    </script>
</head>
<body>
    <h2>Welcome, Approval!</h2>
    <div class="container">
    <!-- Display Pending Order List -->
    <h3>Pending Order List</h3>
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
            while ($row = $pending_order_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['order_id']}</td>";
                echo "<td>{$row['vehicle_id']}</td>";
                echo "<td>{$row['driver_id']}</td>";
                echo "<td>{$row['approval_status']}</td>";
                echo "<td>{$row['order_date']}</td>";
                echo "<td>
                        <button onclick='handleApproval({$row['order_id']}, \"approve\")'>Approve</button>
                        <button onclick='handleApproval({$row['order_id']}, \"reject\")'>Reject</button>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Display Approved Order List -->
    <h3>Approved Order List</h3>
    <div id="approvedOrderList">
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
            <?php
            while ($row = $approved_order_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['order_id']}</td>";
                echo "<td>{$row['vehicle_id']}</td>";
                echo "<td>{$row['driver_id']}</td>";
                echo "<td>{$row['approval_status']}</td>";
                echo "<td>{$row['order_date']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <!-- Display Rejected Order List -->
    <h3>Rejected Order List</h3>
    <div id="rejectedOrderList">
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
            <?php
            while ($row = $rejected_order_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['order_id']}</td>";
                echo "<td>{$row['vehicle_id']}</td>";
                echo "<td>{$row['driver_id']}</td>";
                echo "<td>{$row['approval_status']}</td>";
                echo "<td>{$row['order_date']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <a class="logout-button" href="../logout.php">Logout</a>
    </div>
</body>
</html>
