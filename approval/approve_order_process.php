
<?php
session_start();

// Check if the user is logged in and has the approval role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'approver') {
    header('Location: ../login.php');
    exit();
}

include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id']) && isset($_GET['action'])) {
    $order_id = $_GET['order_id'];
    $action = $_GET['action'];

    // Update order status based on the action (approve/reject)
    $update_status = ($action == 'approve') ? 'approved' : 'rejected';

    $update_query = $conn->prepare("UPDATE orders SET approval_status = ? WHERE order_id = ?");
    $update_query->bind_param("si", $update_status, $order_id);
    $update_query->execute();

    if ($update_query->error) {
        echo json_encode(['error' => 'Error updating order status: ' . $update_query->error]);
    } else {
        // Fetch updated list of approved orders
        $approved_order_query = "SELECT * FROM orders WHERE approval_status = 'approved'";
        $approved_order_result = $conn->query($approved_order_query);

        // Fetch updated list of rejected orders
        $rejected_order_query = "SELECT * FROM orders WHERE approval_status = 'rejected'";
        $rejected_order_result = $conn->query($rejected_order_query);

        
        echo json_encode([
            'approvedOrderList' => fetchOrdersHTML($approved_order_result),
            'rejectedOrderList' => fetchOrdersHTML($rejected_order_result),
        ]);
    }
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
?>
