<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$orderId = $_POST['order_id'] ?? 0;
$paymentMethod = $_POST['payment_method'] ?? '';

if (!$orderId || !$paymentMethod) {
    echo json_encode(['success' => false, 'message' => 'Missing required information']);
    exit();
}

// Get order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
$stmt->bind_param("ii", $orderId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit();
}

$order = $result->fetch_assoc();

// Check if order is confirmed by service provider
if ($order['status'] !== 'confirmed') {
    echo json_encode(['success' => false, 'message' => 'Service provider has not confirmed this order yet']);
    exit();
}

// Check if already paid
if ($order['payment_status'] === 'completed') {
    echo json_encode(['success' => false, 'message' => 'Payment already completed for this order']);
    exit();
}

// Process payment based on method
if ($paymentMethod === 'gcash' || $paymentMethod === 'paymaya' || $paymentMethod === 'online_banking') {
    // For online payment methods, simulate payment gateway integration
    // In production, integrate with actual GCash/PayMaya/banking APIs
    
    // Update payment status to completed
    $updateStmt = $conn->prepare("UPDATE orders SET payment_status = 'completed', payment_method = ? WHERE id = ?");
    $updateStmt->bind_param("si", $paymentMethod, $orderId);
    
    if ($updateStmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Payment processed successfully',
            'order_id' => $orderId,
            'payment_status' => 'completed'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to process payment']);
    }
    $updateStmt->close();
    
} else if ($paymentMethod === 'cod') {
    // Cash on Delivery - mark as pending payment
    $updateStmt = $conn->prepare("UPDATE orders SET payment_status = 'pending', payment_method = 'Cash on Delivery' WHERE id = ?");
    $updateStmt->bind_param("i", $orderId);
    
    if ($updateStmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cash on Delivery confirmed. Pay ₱' . $order['total_amount'] . ' when rider delivers',
            'order_id' => $orderId,
            'payment_method' => 'cod',
            'amount' => $order['total_amount']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to process payment']);
    }
    $updateStmt->close();
}

$stmt->close();
$conn->close();
?>
