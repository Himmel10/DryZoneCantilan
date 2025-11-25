<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get cart items
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: modules/cart.php');
    exit();
}

$cartItems = $_SESSION['cart'];
$subtotal = 0;

foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Auto delivery fee logic
$deliveryFee = $subtotal >= 300 ? 0 : 50;
$totalAmount = $subtotal + $deliveryFee;

// Get user info if logged in
$userName = '';
$userEmail = '';
$userPhone = '';
if (isset($_SESSION['user_id'])) {
    $result = $conn->query("SELECT full_name, email FROM users WHERE id = " . intval($_SESSION['user_id']));
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userName = $user['full_name'] ?? '';
        $userEmail = $user['email'] ?? '';
        $userPhone = ''; // Phone not stored in users table
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <style>
        .checkout-container {
            max-width: 1000px;
            margin: 25px auto;
            padding: 0 20px;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .checkout-header h1 {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            margin-bottom: 25px;
        }

        .checkout-form {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-section h3 {
            color: var(--primary);
            font-size: 1.2rem;
            margin-bottom: 15px;
            border-bottom: 2px solid var(--light);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--secondary);
            font-weight: 500;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--light);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .order-summary {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .order-summary h3 {
            color: var(--primary);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--light);
            padding-bottom: 10px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
            color: #666;
        }

        .summary-item.total {
            border-top: 2px solid var(--light);
            padding-top: 12px;
            font-size: 1.1rem;
            color: var(--primary);
            font-weight: bold;
        }

        .submit-btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: var(--secondary);
        }

        .notice {
            background: var(--light);
            border-left: 4px solid var(--accent);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: var(--secondary);
            font-size: 0.9rem;
        }

        .cart-item-summary {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .cart-item-summary strong {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <div class="checkout-header">
        <h1><i class="fas fa-credit-card"></i> Checkout</h1>
        <p>Complete your order</p>
    </div>

    <div class="checkout-content">
        <form class="checkout-form" id="checkoutForm" method="POST" action="submit_order_from_cart.php">
            <!-- Delivery Notice -->
            <div class="notice">
                <strong>💰 Pay on Delivery:</strong> No payment needed now. Pay when you pick up or receive your laundry.
            </div>

            <!-- Customer Information -->
            <div class="form-section">
                <h3><i class="fas fa-user"></i> Delivery Information</h3>
                
                <div class="form-group">
                    <label for="customerName">Full Name *</label>
                    <input type="text" id="customerName" name="customerName" value="<?php echo htmlspecialchars($userName); ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="customerPhone">Phone Number *</label>
                        <input type="tel" id="customerPhone" name="customerPhone" value="<?php echo htmlspecialchars($userPhone); ?>" placeholder="09XX XXX XXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="customerEmail">Email *</label>
                        <input type="email" id="customerEmail" name="customerEmail" value="<?php echo htmlspecialchars($userEmail); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="customerAddress">Delivery Address *</label>
                    <textarea id="customerAddress" name="customerAddress" placeholder="Enter your complete address" rows="2" required></textarea>
                </div>
            </div>

            <!-- Delivery Options -->
            <div class="form-section">
                <h3><i class="fas fa-truck"></i> Delivery Option</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label style="border: 2px solid #e9ecef; border-radius: 5px; padding: 15px; cursor: pointer; text-align: center; transition: all 0.2s;">
                        <input type="radio" name="delivery_option" value="pickup" checked style="margin-right: 8px;">
                        <strong style="color: var(--primary);">Pickup</strong><br>
                        <small style="color: #666;">FREE</small>
                    </label>
                    <label style="border: 2px solid #e9ecef; border-radius: 5px; padding: 15px; cursor: pointer; text-align: center; transition: all 0.2s;">
                        <input type="radio" name="delivery_option" value="delivery" style="margin-right: 8px;">
                        <strong style="color: var(--primary);">Delivery</strong><br>
                        <small style="color: #666;">₱50 (FREE if ≥₱300)</small>
                    </label>
                </div>
            </div>

            <!-- Special Instructions -->
            <div class="form-section">
                <h3><i class="fas fa-sticky-note"></i> Special Instructions</h3>
                
                <div class="form-group">
                    <textarea id="specialInstructions" name="specialInstructions" placeholder="Any special care instructions..." rows="2"></textarea>
                </div>
            </div>

            <input type="hidden" id="cartData" name="cartData" value="<?php echo htmlspecialchars(json_encode($cartItems)); ?>">
        </form>

        <!-- Order Summary -->
        <div class="order-summary">
            <h3><i class="fas fa-receipt"></i> Order Summary</h3>
            
            <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item-summary">
                        <strong><?php echo htmlspecialchars($item['service']); ?></strong><br>
                        Shop: <?php echo htmlspecialchars($item['shop']); ?><br>
                        Qty: <?php echo $item['quantity']; ?> × ₱<?php echo number_format($item['price'], 2); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-item">
                <span>Subtotal</span>
                <span>₱<?php echo number_format($subtotal, 2); ?></span>
            </div>

            <div class="summary-item">
                <span>Delivery Fee</span>
                <span id="deliveryFeeDisplay">₱<?php echo number_format($deliveryFee, 2); ?></span>
            </div>

            <div class="summary-item total">
                <span>Total Amount</span>
                <span id="totalDisplay">₱<?php echo number_format($totalAmount, 2); ?></span>
            </div>

            <small style="display: block; color: #666; margin-top: 15px; text-align: center;">
                ⚠️ This is an estimate. Final amount calculated at pickup/delivery based on actual weight.
            </small>

            <button type="submit" class="submit-btn" form="checkoutForm">
                <i class="fas fa-check"></i> Confirm Order
            </button>

            <div style="text-align: center; margin-top: 15px;">
                <a href="modules/cart.php" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const subtotal = <?php echo $subtotal; ?>;
    const deliveryOptionRadios = document.querySelectorAll('input[name="delivery_option"]');

    deliveryOptionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const deliveryFee = this.value === 'delivery' ? (subtotal >= 300 ? 0 : 50) : 0;
            const total = subtotal + deliveryFee;
            
            document.getElementById('deliveryFeeDisplay').textContent = '₱' + deliveryFee.toFixed(2);
            document.getElementById('totalDisplay').textContent = '₱' + total.toFixed(2);
        });
    });

    // Confirmation is now handled by js/auth.js
</script>

</body>
</html>
