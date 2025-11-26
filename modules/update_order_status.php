<?php
header('Content-Type: application/json');
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$order_id = (int)($_POST['order_id'] ?? 0);
$new_status = $_POST['status'] ?? '';
$sellerName = $_SESSION['user_name'];

$valid_statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
if (!in_array($new_status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

$stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND shop_name = ?");
$stmt->bind_param("is", $order_id, $sellerName);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

$old_status = $order['status'];

$update_stmt = $conn->prepare("
    UPDATE orders 
    SET status = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ? AND shop_name = ?
");

$update_stmt->bind_param("sis", $new_status, $order_id, $sellerName);

if (!$update_stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to update order: ' . $update_stmt->error]);
    $update_stmt->close();
    $conn->close();
    exit;
}

$update_stmt->close();

$changed_by = $_SESSION['user_name'] ?? 'System';
$sanitized_notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');

$history_stmt = $conn->prepare("
    INSERT INTO order_status_history (order_id, old_status, new_status, changed_by, notes)
    VALUES (?, ?, ?, ?, ?)
");

$history_stmt->bind_param("issss", $order_id, $old_status, $new_status, $changed_by, $sanitized_notes);

if (!$history_stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to record status change']);
    $history_stmt->close();
    $conn->close();
    exit;
}

$history_stmt->close();

$email_stmt = $conn->prepare("
    SELECT u.email, o.customer_id 
    FROM orders o 
    JOIN users u ON o.customer_id = u.id 
    WHERE o.id = ?
");

$email_stmt->bind_param("i", $order_id);
$email_stmt->execute();
$email_result = $email_stmt->get_result();
$email_row = $email_result->fetch_assoc();
$email_stmt->close();

if ($email_row && $email_row['email']) {
    $to = $email_row['email'];
    $subject = "Order #$order_id Status Updated - Dry Zone Cantilan";
    $message = "Your order #$order_id status has been updated to: " . ucfirst($new_status);
    
    if (!empty($notes)) {
        $message .= "\n\nNote: " . $notes;
    }
    
    $headers = "From: no-reply@dryzonecantilan.local\r\nContent-Type: text/plain; charset=UTF-8";
    
}

$conn->close();

echo json_encode([
    'success' => true,
    'message' => 'Order status updated successfully',
    'order_id' => $order_id,
    'old_status' => $old_status,
    'new_status' => $new_status,
    'updated_at' => date('Y-m-d H:i:s')
]);
?>
