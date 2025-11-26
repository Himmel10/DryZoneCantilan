<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$customerName = $_POST['customerName'] ?? '';
$customerPhone = $_POST['customerPhone'] ?? '';
$customerEmail = $_POST['customerEmail'] ?? '';
$customerAddress = $_POST['customerAddress'] ?? '';
$deliveryOption = $_POST['delivery_option'] ?? 'pickup_delivery';
$paymentMethod = $_POST['payment_method'] ?? 'gcash';
$specialInstructions = $_POST['specialInstructions'] ?? '';
$cartData = json_decode($_POST['cartData'] ?? '[]', true);

if (!$customerName || !$customerPhone || !$customerEmail || !$customerAddress || empty($cartData)) {
    echo json_encode(['success' => false, 'message' => 'Missing required information']);
    exit();
}

$subtotal = 0;
$serviceList = [];

foreach ($cartData as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $serviceList[] = $item['service'] . ' (x' . $item['quantity'] . ')';
}

$deliveryFee = 50; // Mandatory ₱50 for pickup & delivery
$totalAmount = $subtotal + $deliveryFee;

$shopName = $cartData[0]['shop'] ?? 'Multiple Shops';

$stmt = $conn->prepare("INSERT INTO orders (customer_id, customer_name, customer_email, customer_phone, customer_address, shop_name, service_type, total_amount, payment_method, notes, status, payment_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$services = implode(', ', $serviceList);
$deliveryNote = 'Rider Pickup & Delivery: ₱' . $deliveryFee . ' | Special Instructions: ' . $specialInstructions;
$paymentMethodLabel = match($paymentMethod) {
    'gcash' => 'GCash',
    'paymaya' => 'PayMaya',
    'online_banking' => 'Online Banking',
    'cod' => 'Cash on Delivery',
    default => 'GCash'
};

$stmt->bind_param('issssssdss', 
    $_SESSION['user_id'],
    $customerName,
    $customerEmail,
    $customerPhone,
    $customerAddress,
    $shopName,
    $services,
    $totalAmount,
    $paymentMethodLabel,
    $deliveryNote
);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id;
    
    $_SESSION['cart'] = [];
    
    if (isset($_POST['json'])) {
        echo json_encode(['success' => true, 'orderId' => $orderId, 'message' => 'Order placed successfully']);
    } else {
        header('Location: customer_orders.php?success=' . $orderId);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create order: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
