<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Get form data
$customerName = $_POST['customerName'] ?? '';
$customerPhone = $_POST['customerPhone'] ?? '';
$customerEmail = $_POST['customerEmail'] ?? '';
$customerAddress = $_POST['customerAddress'] ?? '';
$deliveryOption = $_POST['delivery_option'] ?? 'pickup';
$specialInstructions = $_POST['specialInstructions'] ?? '';
$cartData = json_decode($_POST['cartData'] ?? '[]', true);

// Validate input
if (!$customerName || !$customerPhone || !$customerEmail || !$customerAddress || empty($cartData)) {
    echo json_encode(['success' => false, 'message' => 'Missing required information']);
    exit();
}

// Calculate totals
$subtotal = 0;
$serviceList = [];

foreach ($cartData as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $serviceList[] = $item['service'] . ' (x' . $item['quantity'] . ')';
}

$deliveryFee = ($deliveryOption === 'delivery' && $subtotal < 300) ? 50 : 0;
$totalAmount = $subtotal + $deliveryFee;

// Get primary shop from first cart item
$shopName = $cartData[0]['shop'] ?? 'Multiple Shops';

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (customer_id, shop_name, service_type, total_amount, payment_method, notes, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$services = implode(', ', $serviceList);
$deliveryNote = 'Delivery: ' . ($deliveryOption === 'delivery' ? 'Delivery ₱' . $deliveryFee : 'Pickup (Free)') . ' | Address: ' . $customerAddress . ' | ' . $specialInstructions;
$paymentMethod = 'Cash on Delivery';

$stmt->bind_param('issdss', 
    $_SESSION['user_id'],
    $shopName,
    $services,
    $totalAmount,
    $paymentMethod,
    $deliveryNote
);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id;
    
    // Clear cart
    $_SESSION['cart'] = [];
    
    // Redirect based on request type
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
