<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $service = $_POST['service'] ?? '';
    $shop = $_POST['shop'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($service && $shop && $price > 0 && $quantity > 0) {
        // Check if service already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['service'] === $service && $item['shop'] === $shop) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // Add new item if not found
        if (!$found) {
            $_SESSION['cart'][] = [
                'service' => $service,
                'shop' => $shop,
                'price' => $price,
                'quantity' => $quantity
            ];
        }

        echo json_encode(['success' => true, 'cartCount' => count($_SESSION['cart'])]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
    }
}

elseif ($action === 'get_count') {
    echo json_encode(['count' => count($_SESSION['cart'])]);
}

elseif ($action === 'update') {
    $index = intval($_POST['index'] ?? -1);
    $change = intval($_POST['change'] ?? 0);

    if ($index >= 0 && $index < count($_SESSION['cart'])) {
        $_SESSION['cart'][$index]['quantity'] += $change;
        
        if ($_SESSION['cart'][$index]['quantity'] <= 0) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}

elseif ($action === 'remove') {
    $index = intval($_POST['index'] ?? -1);

    if ($index >= 0 && $index < count($_SESSION['cart'])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}

elseif ($action === 'clear') {
    $_SESSION['cart'] = [];
    echo json_encode(['success' => true]);
}

else {
    echo json_encode(['success' => false, 'message' => 'Unknown action']);
}

