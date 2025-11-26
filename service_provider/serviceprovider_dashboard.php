<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'seller') {
    header('Location: ../login.php');
    exit();
}

$sellerId = $_SESSION['user_id'];
$sellerName = "Daily BubbleBox Laundry Hub"; // Shop1 owner

$ordersQuery = "SELECT COUNT(*) as total FROM orders WHERE shop_name = ?";
$ordersStmt = $conn->prepare($ordersQuery);
$ordersStmt->bind_param("s", $sellerName);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();
$ordersData = $ordersResult->fetch_assoc();
$totalOrders = $ordersData['total'] ?? 0;

// Get pending orders
$pendingQuery = "SELECT COUNT(*) as total FROM orders WHERE status = 'pending' AND shop_name = ?";
$pendingStmt = $conn->prepare($pendingQuery);
$pendingStmt->bind_param("s", $sellerName);
$pendingStmt->execute();
$pendingResult = $pendingStmt->get_result();
$pendingData = $pendingResult->fetch_assoc();
$pendingOrders = $pendingData['total'] ?? 0;

// Get completed orders
$completedQuery = "SELECT COUNT(*) as total FROM orders WHERE status = 'completed' AND shop_name = ?";
$completedStmt = $conn->prepare($completedQuery);
$completedStmt->bind_param("s", $sellerName);
$completedStmt->execute();
$completedResult = $completedStmt->get_result();
$completedData = $completedResult->fetch_assoc();
$completedOrders = $completedData['total'] ?? 0;

// Get total revenue
$revenueQuery = "SELECT SUM(total_amount) as revenue FROM orders WHERE status = 'completed' AND shop_name = ?";
$revenueStmt = $conn->prepare($revenueQuery);
$revenueStmt->bind_param("s", $sellerName);
$revenueStmt->execute();
$revenueResult = $revenueStmt->get_result();
$revenueData = $revenueResult->fetch_assoc();
$totalRevenue = $revenueData['revenue'] ?? 0;

// Get recent orders
$recentQuery = "SELECT id, customer_name, service_type, total_amount, status, created_at FROM orders WHERE shop_name = ? ORDER BY created_at DESC LIMIT 10";
$recentStmt = $conn->prepare($recentQuery);
$recentStmt->bind_param("s", $sellerName);
$recentStmt->execute();
$recentResult = $recentStmt->get_result();
$recentOrders = $recentResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .dashboard-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f2f6 0%, #f5f7fa 100%);
            padding: 20px;
        }
        .dashboard-header {
            background: white;
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .dashboard-header h1 {
            color: var(--primary);
            margin: 0;
            font-size: 1.8rem;
        }
        .dashboard-header p {
            color: var(--medium);
            margin: 5px 0 0 0;
        }
        .dashboard-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(39,77,96,0.12);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }
        .stat-label {
            color: var(--medium);
            font-size: 0.95rem;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-value {
            color: var(--primary);
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(39,77,96,0.08);
            margin-bottom: 30px;
        }
        .content-section h2 {
            color: var(--primary);
            margin: 0 0 20px 0;
            font-size: 1.5rem;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table thead {
            background: var(--light);
        }
        .orders-table th {
            padding: 15px;
            text-align: left;
            color: var(--primary);
            font-weight: 600;
            border-bottom: 2px solid var(--accent);
        }
        .orders-table td {
            padding: 15px;
            border-bottom: 1px solid var(--accent);
        }
        .order-id {
            font-weight: 700;
            color: var(--primary);
        }
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--medium);
        }
        .empty-state i {
            font-size: 4rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--medium);
            font-weight: 600;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e4eef1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
        }
        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .btn-logout {
            background: var(--danger);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-logout:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .dashboard-actions {
                width: 100%;
                flex-direction: column;
            }
            .dashboard-actions a {
                width: 100%;
                text-align: center;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .orders-table {
                font-size: 0.9rem;
            }
            .orders-table th, .orders-table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="container">
            <div class="dashboard-header">
                <div>
                    <h1><i class="fas fa-store"></i> Daily BubbleBox Laundry Hub - Dashboard</h1>
                    <p>Welcome, Shop Owner! | Poblacion, Cantilan</p>
                </div>
                <div class="dashboard-actions">
                    <a href="../shop1.php" class="btn"><i class="fas fa-eye"></i> View Shop</a>
                    <a href="../index.php" class="btn"><i class="fas fa-home"></i> Home</a>
                    <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                    <p class="stat-label">Total Orders</p>
                    <p class="stat-value"><?php echo $totalOrders; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <p class="stat-label">Pending Orders</p>
                    <p class="stat-value"><?php echo $pendingOrders; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <p class="stat-label">Completed Orders</p>
                    <p class="stat-value"><?php echo $completedOrders; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-peso-sign"></i></div>
                    <p class="stat-label">Total Revenue</p>
                    <p class="stat-value">₱<?php echo number_format($totalRevenue, 2); ?></p>
                </div>
            </div>

            <div class="content-section" style="background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%); border-left: 4px solid #10b981;">
                <h2><i class="fas fa-check-circle" style="color: #10b981;"></i> Recently Confirmed Order</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px; background: white; border-radius: 12px; margin-bottom: 20px;">
                    <div>
                        <h3 style="margin: 0 0 15px 0; color: var(--primary);">Order Details</h3>
                        <div style="line-height: 1.8;">
                            <p><strong>Order ID:</strong> #000020</p>
                            <p><strong>Customer:</strong> Maria Santos</p>
                            <p><strong>Status:</strong> <span class="status-badge" style="background: #d1fae5; color: #065f46;">✓ Confirmed</span></p>
                            <p><strong>Services:</strong> Wash & Fold (3kg)</p>
                            <p><strong>Amount:</strong> ₱134.00</p>
                            <p><strong>Payment:</strong> GCash (Payment Pending)</p>
                        </div>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 15px 0; color: var(--primary);">Delivery Details</h3>
                        <div style="line-height: 1.8;">
                            <p><strong>Address:</strong> 123 Poblacion St., Cantilan, Surigao del Sur</p>
                            <p><strong>Phone:</strong> 09171234567</p>
                            <p><strong>Pickup Time:</strong> Today, 10:00 AM</p>
                            <p><strong>Expected Delivery:</strong> Tomorrow, 3:00 PM</p>
                            <p><strong>Special Instructions:</strong> Handle with care - delicate items</p>
                            <p><strong>Rider Fee:</strong> ₱50.00</p>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button onclick="updateStatus(20, 'processing')" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; flex: 1; min-width: 150px;">
                        <i class="fas fa-arrow-right"></i> Mark as Processing
                    </button>
                    <button onclick="sendNotification()" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; flex: 1; min-width: 150px;">
                        <i class="fas fa-bell"></i> Notify Customer
                    </button>
                    <button onclick="printOrder()" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; flex: 1; min-width: 150px;">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-shopping-bag"></i> Recent Orders</h2>
                <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <input type="text" id="searchInput" placeholder="Search by order ID or service..." style="flex: 1; min-width: 200px; padding: 10px; border: 2px solid #e4eef1; border-radius: 8px;">
                    <select id="statusFilter" style="padding: 10px; border: 2px solid #e4eef1; border-radius: 8px;">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <?php if (count($recentOrders) > 0): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Service Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td class="order-id">Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($order['service_type']); ?></td>
                                    <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <?php if ($order['status'] === 'pending'): ?>
                                            <button class="btn-action" onclick="confirmOrder(<?php echo $order['id']; ?>)" style="background: #10b981; color: white; padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">Confirm</button>
                                        <?php elseif ($order['status'] === 'confirmed'): ?>
                                            <button class="btn-action" onclick="updateStatus(<?php echo $order['id']; ?>, 'processing')" style="background: #3b82f6; color: white; padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">Processing</button>
                                        <?php elseif ($order['status'] === 'processing'): ?>
                                            <button class="btn-action" onclick="updateStatus(<?php echo $order['id']; ?>, 'completed')" style="background: #8b5cf6; color: white; padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">Complete</button>
                                        <?php endif; ?>
                                        <button class="btn-action" onclick="viewDetails(<?php echo $order['id']; ?>)" style="background: #6b7280; color: white; padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">Details</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>No orders yet</h3>
                        <p>Orders will appear here once customers place them.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-chart-bar"></i> Performance Analytics</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div style="background: #f0f9ff; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6;">
                        <p style="margin: 0 0 10px 0; color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Today's Orders</p>
                        <p style="margin: 0; color: #3b82f6; font-size: 1.8rem; font-weight: 700;">
                            <?php 
                                $todayQuery = "SELECT COUNT(*) as count FROM orders WHERE shop_name = ? AND DATE(created_at) = CURDATE()";
                                $todayStmt = $conn->prepare($todayQuery);
                                $todayStmt->bind_param("s", $sellerName);
                                $todayStmt->execute();
                                $todayResult = $todayStmt->get_result();
                                $todayData = $todayResult->fetch_assoc();
                                echo $todayData['count'] ?? 0;
                            ?>
                        </p>
                    </div>
                    <div style="background: #f0fdf4; padding: 20px; border-radius: 12px; border-left: 4px solid #10b981;">
                        <p style="margin: 0 0 10px 0; color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">This Week</p>
                        <p style="margin: 0; color: #10b981; font-size: 1.8rem; font-weight: 700;">
                            <?php 
                                $weekQuery = "SELECT COUNT(*) as count FROM orders WHERE shop_name = ? AND WEEK(created_at) = WEEK(NOW())";
                                $weekStmt = $conn->prepare($weekQuery);
                                $weekStmt->bind_param("s", $sellerName);
                                $weekStmt->execute();
                                $weekResult = $weekStmt->get_result();
                                $weekData = $weekResult->fetch_assoc();
                                echo $weekData['count'] ?? 0;
                            ?>
                        </p>
                    </div>
                    <div style="background: #fef3c7; padding: 20px; border-radius: 12px; border-left: 4px solid #f59e0b;">
                        <p style="margin: 0 0 10px 0; color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Avg Rating</p>
                        <p style="margin: 0; color: #f59e0b; font-size: 1.8rem; font-weight: 700;">
                            <?php 
                                $ratingQuery = "SELECT AVG(rating) as avg_rating FROM reviews WHERE shop_name = ?";
                                $ratingStmt = $conn->prepare($ratingQuery);
                                $ratingStmt->bind_param("s", $sellerName);
                                $ratingStmt->execute();
                                $ratingResult = $ratingStmt->get_result();
                                $ratingData = $ratingResult->fetch_assoc();
                                echo number_format($ratingData['avg_rating'] ?? 0, 1);
                            ?>
                            <i class="fas fa-star" style="color: #f59e0b; font-size: 1rem;"></i>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmOrder(orderId) {
            if (confirm('Are you sure you want to confirm this order?')) {
                updateOrderStatus(orderId, 'confirmed');
            }
        }

        function updateStatus(orderId, status) {
            const statusText = status === 'processing' ? 'Mark as Processing' : 'Mark as Completed';
            if (confirm(`Are you sure you want to ${statusText}?`)) {
                updateOrderStatus(orderId, status);
            }
        }

        function updateOrderStatus(orderId, status) {
            fetch('../modules/update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `order_id=${orderId}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order status updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating order: ' + data.message);
                }
            })
            .catch(error => alert('Error: ' + error.message));
        }

        function viewDetails(orderId) {
            // In production, this would open an order details modal or page
            alert('Order #' + String(orderId).padStart(6, '0') + ' details would open here');
        }

        function sendNotification() {
            alert('✓ Notification sent to customer Maria Santos!\n\nMessage: "Your order #000020 has been confirmed. We will pick up at 10:00 AM today."');
        }

        function printOrder() {
            alert('🖨️ Printing order receipt for Order #000020...\n\nCustomer: Maria Santos\nService: Wash & Fold (3kg)\nAmount: ₱134.00\nDelivery Fee: ₱50.00\nTotal: ₱184.00');
            // In production, this would open a print dialog
            window.print();
        }

        // Search and filter functionality
        document.getElementById('searchInput')?.addEventListener('input', function() {
            filterOrders();
        });
        document.getElementById('statusFilter')?.addEventListener('change', function() {
            filterOrders();
        });

        function filterOrders() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const statusFilter = document.getElementById('statusFilter')?.value || '';
            const rows = document.querySelectorAll('.orders-table tbody tr');

            rows.forEach(row => {
                const orderId = row.cells[0]?.textContent.toLowerCase() || '';
                const service = row.cells[2]?.textContent.toLowerCase() || '';
                const status = row.cells[4]?.textContent.toLowerCase() || '';

                const matchesSearch = orderId.includes(searchTerm) || service.includes(searchTerm);
                const matchesStatus = !statusFilter || status.includes(statusFilter);

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }
    </script>

