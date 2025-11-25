<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify order belongs to user and is pending
    $checkQuery = "SELECT id, status FROM orders WHERE id = ? AND customer_id = ? AND status = 'pending'";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $order_id, $user_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update order status to cancelled
        $cancelQuery = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
        $cancelStmt = $conn->prepare($cancelQuery);
        $cancelStmt->bind_param("i", $order_id);
        
        if ($cancelStmt->execute()) {
            $_SESSION['success_message'] = 'Order has been cancelled successfully.';
            header('Location: customer_orders.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'Failed to cancel order. Please try again.';
            header('Location: customer_orders.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Order not found or cannot be cancelled.';
        header('Location: customer_orders.php');
        exit();
    }
} else {
    header('Location: customer_orders.php');
    exit();
}
?>
