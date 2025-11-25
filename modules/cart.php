<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = '../';
include '../header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartItems = $_SESSION['cart'];
$totalAmount = 0;

foreach ($cartItems as $item) {
    $itemTotal = $item['price'] * $item['quantity'];
    $totalAmount += $itemTotal;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 25px auto;
            padding: 0 20px;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .cart-header h1 {
            font-size: 2rem;
            color: var(--mid-dark);
            margin-bottom: 8px;
        }

        .cart-content {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            margin-bottom: 25px;
        }

        .cart-items {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-info h4 {
            color: var(--primary);
            margin-bottom: 5px;
        }

        .item-info p {
            color: var(--accent);
            font-size: 0.9rem;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background: var(--light);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            cursor: pointer;
            color: var(--primary);
            font-weight: bold;
            transition: background 0.3s;
        }

        .quantity-btn:hover {
            background: var(--accent);
            color: white;
        }

        .item-price {
            text-align: right;
            min-width: 100px;
        }

        .item-price strong {
            display: block;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .remove-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .remove-btn:hover {
            background: #dc2626;
        }

        .cart-empty {
            text-align: center;
            padding: 60px 20px;
            color: var(--accent);
        }

        .cart-empty i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .cart-summary {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .summary-item.total {
            border-top: 2px solid var(--light);
            padding-top: 15px;
            font-size: 1.2rem;
            color: var(--primary);
            font-weight: bold;
        }

        .checkout-btn {
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

        .checkout-btn:hover {
            background: var(--secondary);
        }

        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .continue-shopping {
            text-align: center;
            margin-top: 20px;
        }

        .continue-shopping a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .continue-shopping a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .cart-content {
                grid-template-columns: 1fr;
            }

            .cart-summary {
                position: static;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .item-price {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>

<div class="cart-container">
    <div class="cart-header">
        <h1><i class="fas fa-shopping-cart"></i> Your Cart</h1>
        <p>Review your selected services before checkout</p>
    </div>

    <div class="cart-content">
        <div class="cart-items" id="cartItems">
            <?php if (count($cartItems) > 0): ?>
                <?php foreach ($cartItems as $index => $item): ?>
                    <div class="cart-item" data-index="<?php echo $index; ?>">
                        <div class="item-info">
                            <h4><?php echo htmlspecialchars($item['service']); ?></h4>
                            <p>Shop: <?php echo htmlspecialchars($item['shop']); ?></p>
                        </div>
                        <div class="item-quantity">
                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $index; ?>, -1)">−</button>
                            <span id="qty-<?php echo $index; ?>"><?php echo $item['quantity']; ?></span>
                            <button class="quantity-btn" onclick="updateQuantity(<?php echo $index; ?>, 1)">+</button>
                        </div>
                        <div class="item-price">
                            <strong>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                            <small>₱<?php echo number_format($item['price'], 2); ?> each</small>
                        </div>
                        <button class="remove-btn" onclick="removeFromCart(<?php echo $index; ?>)">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Add some services to get started!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="cart-summary">
            <h3 style="color: var(--primary); margin-bottom: 20px;">Order Summary</h3>
            
            <div class="summary-item">
                <span>Subtotal</span>
                <span id="subtotal">₱<?php echo number_format($totalAmount, 2); ?></span>
            </div>

            <div class="summary-item">
                <span>Delivery Fee</span>
                <span id="deliveryFee">₱50.00</span>
            </div>

            <div class="summary-item total">
                <span>Total</span>
                <span id="totalPrice">₱<?php echo number_format($totalAmount + 50, 2); ?></span>
            </div>

            <button class="checkout-btn" id="checkoutBtn" <?php echo count($cartItems) === 0 ? 'disabled' : ''; ?>>
                Proceed to Checkout
            </button>

            <div class="continue-shopping">
                <a href="../index.php"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
            </div>
        </div>
    </div>
</div>

<script>
    function updateQuantity(index, change) {
        fetch('../modules/cart_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=update&index=' + index + '&change=' + change
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function removeFromCart(index) {
        if (confirm('Remove this service from cart?')) {
            fetch('../modules/cart_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=remove&index=' + index
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    document.getElementById('checkoutBtn').addEventListener('click', function() {
        window.location.href = '../checkout.php';
    });
</script>

</body>
</html>
