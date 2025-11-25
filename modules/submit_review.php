<?php
header('Content-Type: application/json');
session_start();

try {
    require_once '../db.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No database connection']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$shop = isset($_POST['shop']) ? trim($_POST['shop']) : '';
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if (empty($shop) || $rating < 1 || $rating > 5 || empty($comment)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing or invalid data']);
    exit;
}

// Ensure reviews table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'reviews'");
if (!$tableCheck || $tableCheck->num_rows == 0) {
    $reviewsSql = "CREATE TABLE IF NOT EXISTS `reviews` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `shop_name` varchar(255) NOT NULL,
        `user_id` int(11) DEFAULT NULL,
        `user_name` varchar(255) DEFAULT NULL,
        `rating` tinyint(1) NOT NULL,
        `comment` text,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `shop_name` (`shop_name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!$conn->query($reviewsSql)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Table creation failed: ' . $conn->error]);
        exit;
    }
}

$user_id = NULL;
$user_name = 'Guest';

if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $user_id = intval($row['id']);
            $user_name = $row['full_name'];
        }
        $stmt->close();
    }
}

$insert = $conn->prepare("INSERT INTO reviews (shop_name, user_id, user_name, rating, comment) VALUES (?, ?, ?, ?, ?)");
if (!$insert) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$insert->bind_param('sisis', $shop, $user_id, $user_name, $rating, $comment);
if ($insert->execute()) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $insert->error]);
}

$insert->close();
$conn->close();
?>
