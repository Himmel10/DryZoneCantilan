<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$orderId = $_GET['order_id'] ?? 0;

if (!$orderId) {
    header('Location: customer_orders.php');
    exit();
}

// Get order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ?");
$stmt->bind_param("ii", $orderId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: customer_orders.php');
    exit();
}

$order = $result->fetch_assoc();

// Check if order is confirmed
$canPay = $order['status'] === 'confirmed' && $order['payment_status'] === 'pending';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Order #<?php echo $orderId; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .payment-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f2f6 0%, #f5f7fa 100%);
            padding: 30px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .payment-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 8px 24px rgba(39,77,96,0.15);
        }
        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .payment-header h1 {
            color: var(--primary);
            margin: 10px 0;
            font-size: 1.8rem;
        }
        .order-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .order-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }
        .order-info-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }
        .payment-methods {
            margin-bottom: 25px;
        }
        .payment-methods h3 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        .payment-option {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .payment-option:hover {
            border-color: var(--primary);
            background: #f8f9fa;
        }
        .payment-option input[type="radio"] {
            margin-right: 10px;
        }
        .payment-option label {
            cursor: pointer;
            display: flex;
            align-items: center;
            margin: 0;
            font-size: 0.95rem;
        }
        .payment-option i {
            font-size: 1.2rem;
            color: var(--primary);
            margin-right: 10px;
        }
        .cod-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #856404;
        }
        .pay-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }
        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }
        .pay-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
        }
        @media (max-width: 480px) {
            .payment-card {
                padding: 25px;
            }
            .payment-header h1 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="payment-container">
    <div class="payment-card">
        <div class="payment-header">
            <i class="fas fa-credit-card" style="font-size: 2.5rem; color: var(--primary);"></i>
            <h1>Payment</h1>
            <p style="color: #666; margin: 0;">Order #<?php echo $orderId; ?></p>
        </div>

        <?php if (!$canPay): ?>
            <div class="alert alert-warning">
                <?php if ($order['status'] !== 'confirmed'): ?>
                    <i class="fas fa-info-circle"></i> Waiting for service provider confirmation...
                <?php elseif ($order['payment_status'] === 'completed'): ?>
                    <i class="fas fa-check-circle"></i> Payment completed! Your order is confirmed.
                <?php endif; ?>
            </div>
            <div class="back-link">
                <a href="customer_orders.php"><i class="fas fa-arrow-left"></i> Back to Orders</a>
            </div>
        <?php else: ?>
            <form id="paymentForm">
                <div class="order-info">
                    <div class="order-info-row">
                        <span>Subtotal</span>
                        <span>₱<?php echo number_format($order['total_amount'] - 50, 2); ?></span>
                    </div>
                    <div class="order-info-row">
                        <span>Rider Pickup & Delivery</span>
                        <span>₱50.00</span>
                    </div>
                    <div class="order-info-row">
                        <span>Total Amount</span>
                        <span>₱<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>

                <div class="payment-methods">
                    <h3>Select Payment Method</h3>

                    <div class="payment-option">
                        <label>
                            <input type="radio" name="payment_method" value="gcash" checked>
                            <i class="fas fa-mobile-alt"></i>
                            <span>GCash</span>
                        </label>
                    </div>

                    <div class="payment-option">
                        <label>
                            <input type="radio" name="payment_method" value="paymaya">
                            <i class="fas fa-wallet"></i>
                            <span>PayMaya</span>
                        </label>
                    </div>

                    <div class="payment-option">
                        <label>
                            <input type="radio" name="payment_method" value="online_banking">
                            <i class="fas fa-university"></i>
                            <span>Online Banking</span>
                        </label>
                    </div>

                    <div class="payment-option">
                        <label>
                            <input type="radio" name="payment_method" value="cod">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Cash on Delivery</span>
                        </label>
                        <div class="cod-note" style="margin-left: 32px;">
                            You will pay ₱<?php echo number_format($order['total_amount'], 2); ?> to the rider when they deliver your laundry
                        </div>
                    </div>
                </div>

                <button type="submit" class="pay-button">
                    <i class="fas fa-lock"></i> Complete Payment - ₱<?php echo number_format($order['total_amount'], 2); ?>
                </button>
            </form>

            <div class="back-link">
                <a href="customer_orders.php"><i class="fas fa-arrow-left"></i> Back to Orders</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
<?php if ($canPay): ?>
document.getElementById('paymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const button = this.querySelector('button[type="submit"]');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
        const response = await fetch('modules/process_payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=<?php echo $orderId; ?>&payment_method=${paymentMethod}`
        });

        const data = await response.json();

        if (data.success) {
            if (paymentMethod === 'cod') {
                alert('Cash on Delivery confirmed!\nPay ₱' + data.amount + ' to the rider when they deliver your laundry.');
            } else {
                alert('Payment processed successfully!\nYour order is confirmed.');
            }
            window.location.href = 'customer_orders.php?paid=<?php echo $orderId; ?>';
        } else {
            alert('Payment failed: ' + data.message);
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-lock"></i> Complete Payment - ₱<?php echo number_format($order['total_amount'], 2); ?>';
        }
    } catch (error) {
        alert('Error processing payment: ' + error.message);
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-lock"></i> Complete Payment - ₱<?php echo number_format($order['total_amount'], 2); ?>';
    }
});
<?php endif; ?>
</script>

</body>
</html>
