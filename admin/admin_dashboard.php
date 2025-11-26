<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalSellers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller'")->fetch_assoc()['count'];
$totalCustomers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'];
$totalOrders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$totalRevenue = $conn->query("SELECT SUM(total_amount) as revenue FROM orders WHERE status = 'completed'")->fetch_assoc()['revenue'] ?? 0;
$pendingOrders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];

$recentUsers = $conn->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 10");

$flaggedOrders = $conn->query("SELECT id, shop_name, customer_id, status, created_at FROM orders WHERE status = 'cancelled' OR status = 'pending' ORDER BY created_at DESC LIMIT 8");

$activeSellers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['count'];
$newUsers = $conn->query("SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - System Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #e8f2f6 0%, #f5f7fa 100%);
        }
        .dashboard-container {
            min-height: 100vh;
            padding: 30px 20px;
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
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background: var(--light);
        }
        table th {
            padding: 15px;
            text-align: left;
            color: var(--primary);
            font-weight: 600;
            border-bottom: 2px solid var(--accent);
        }
        table td {
            padding: 15px;
            border-bottom: 1px solid var(--accent);
        }
        table tr:hover {
            background: var(--light);
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-admin {
            background: #ef4444;
            color: white;
        }
        .badge-seller {
            background: #f59e0b;
            color: white;
        }
        .badge-customer {
            background: #3b82f6;
            color: white;
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
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
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
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .activity-card {
            background: var(--light);
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
        }
        .activity-label {
            margin: 0 0 8px 0;
            color: var(--medium);
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .activity-value {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .activity-desc {
            margin: 8px 0 0 0;
            color: var(--medium);
            font-size: 0.85rem;
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
            table {
                font-size: 0.9rem;
            }
            table th, table td {
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
                    <h1><i class="fas fa-cogs"></i> System Administration</h1>
                    <p>Manage and monitor system health, users, and orders</p>
                </div>
                <div class="dashboard-actions">
                    <a href="../index.php" class="btn"><i class="fas fa-home"></i> Home</a>
                    <a href="../logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users-cog"></i></div>
                    <p class="stat-label">Total System Users</p>
                    <p class="stat-value"><?php echo $totalUsers; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-store"></i></div>
                    <p class="stat-label">Active Service Providers</p>
                    <p class="stat-value"><?php echo $totalSellers; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                    <p class="stat-label">Total Orders</p>
                    <p class="stat-value"><?php echo $totalOrders; ?></p>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                    <p class="stat-label">Pending Orders</p>
                    <p class="stat-value" style="color: #f59e0b;"><?php echo $pendingOrders; ?></p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-exclamation-triangle"></i> System Issues & Flagged Orders</h2>
                <p style="color: var(--medium); margin-bottom: 20px;">Cancelled and pending orders requiring attention</p>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Shop Name</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($flaggedOrders->num_rows > 0): ?>
                                <?php while ($order = $flaggedOrders->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                    <td><?php echo htmlspecialchars($order['shop_name'] ?? 'Unknown'); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_id'] ?? 'Unknown'); ?></td>
                                    <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="text-align: center; color: var(--medium);">No flagged orders</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-user-check"></i> Recently Registered Users</h2>
                <p style="color: var(--medium); margin-bottom: 20px;">Latest user registrations in the system</p>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $recentUsers->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo $user['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><span class="badge badge-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="fas fa-chart-line"></i> System Activity (Last 30 Days)</h2>
                <p style="color: var(--medium); margin-bottom: 20px;">System growth and activity metrics</p>
                <div class="activity-grid">
                    <div class="activity-card" style="border-left-color: #3b82f6;">
                        <p class="activity-label">New Registrations</p>
                        <p class="activity-value" style="color: #3b82f6;"><?php echo $newUsers; ?></p>
                        <p class="activity-desc">users joined in last 30 days</p>
                    </div>
                    <div class="activity-card" style="border-left-color: #f59e0b;">
                        <p class="activity-label">New Sellers</p>
                        <p class="activity-value" style="color: #f59e0b;"><?php echo $activeSellers; ?></p>
                        <p class="activity-desc">service providers registered</p>
                    </div>
                    <div class="activity-card" style="border-left-color: #10b981;">
                        <p class="activity-label">Total Revenue</p>
                        <p class="activity-value" style="color: #10b981;">₱<?php echo number_format($totalRevenue, 2); ?></p>
                        <p class="activity-desc">from completed orders only</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
