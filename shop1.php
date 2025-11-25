<?php 
session_start();
$is_logged_in = isset($_SESSION['user_id']);
include 'header.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily BubbleBox Laundry Hub | Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/shop1.css">
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>">
<div class="back-home-container">
    <div class="container">
        <a href="index.php" class="back-home-link">
            <i class="fas fa-arrow-left"></i><span>Back to Home</span>
        </a>
    </div>
</div>
<main class="shop-detail-container container">
    <div class="shop-detail-header">
        <a href="./images/bubblebox.png" class="photo-link">
            <img src="./images/bubblebox.png" alt="Daily BubbleBox Laundry Hub" class="shop-detail-img">
        </a>
        <div class="shop-detail-info">
            <h2>Daily BubbleBox Laundry Hub</h2>
            <div class="shop-info"><i class="fas fa-map-marker-alt"></i> Poblacion, Cantilan</div>
            <div class="shop-info"><i class="fas fa-star rating"></i> 4.8 (124 reviews)</div>
            <div class="shop-desc">
                <p>Complete laundry services with free pickup and delivery within Poblacion area. Fast, reliable, and clean!</p>
            </div>
            <div class="shop-extra">
                <span><b>Daily Orders:</b> ~54 customers</span>
                </div>
                <div class="shop-message">
                    <button class="message-btn" onclick="window.location.href='chat.php?shop=Daily BubbleBox Laundry Hub'">
                        <i class="fas fa-envelope"></i> Message Shop
                    </button>
            </div>
        </div>
    </div>
    <section class="shop-services">
        <h3><i class="fas fa-concierge-bell"></i> Available Services</h3>
        <div class="services-grid">
            <!-- Wash & Fold -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <h4>Wash & Fold</h4>
                <p class="service-desc">Standard clothes washing and folding</p>
                <div class="service-price">
                    <strong>₱28/kg</strong>
                    <small>(per kilogram)</small>
                </div>
                <div class="service-features">
                    <small>✓ Fast & reliable</small><br>
                    <small>✓ Free pickup/delivery</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Wash & Fold', 'Daily BubbleBox Laundry Hub', 28)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>

            <!-- Dry Cleaning -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-soap"></i>
                </div>
                <h4>Dry Cleaning</h4>
                <p class="service-desc">Professional dry cleaning for delicate fabrics</p>
                <div class="service-price">
                    <strong>₱180/item</strong>
                    <small>(per piece)</small>
                </div>
                <div class="service-features">
                    <small>✓ Delicate care</small><br>
                    <small>✓ Professional finish</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Dry Cleaning', 'Daily BubbleBox Laundry Hub', 180)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>

            <!-- Ironing -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <h4>Ironing Service</h4>
                <p class="service-desc">Professional ironing and pressing</p>
                <div class="service-price">
                    <strong>₱30/kg</strong>
                    <small>(per kilogram)</small>
                </div>
                <div class="service-features">
                    <small>✓ Crisp finish</small><br>
                    <small>✓ Quick turnaround</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Ironing', 'Daily BubbleBox Laundry Hub', 30)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>

            <!-- Mattress Cleaning -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h4>Mattress Cleaning</h4>
                <p class="service-desc">Deep cleaning for mattresses and large items</p>
                <div class="service-price">
                    <strong>₱250/item</strong>
                    <small>(per mattress)</small>
                </div>
                <div class="service-features">
                    <small>✓ Deep clean</small><br>
                    <small>✓ Sanitized</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Mattress Cleaning', 'Daily BubbleBox Laundry Hub', 250)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>

            <!-- Starch Service -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-spray-can"></i>
                </div>
                <h4>Starch Service</h4>
                <p class="service-desc">Professional starch application</p>
                <div class="service-price">
                    <strong>₱20/kg</strong>
                    <small>(per kilogram)</small>
                </div>
                <div class="service-features">
                    <small>✓ Crisp & stiff</small><br>
                    <small>✓ Professional look</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Starch Service', 'Daily BubbleBox Laundry Hub', 20)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>

            <!-- Delicate Wear -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-feather"></i>
                </div>
                <h4>Delicate Wear</h4>
                <p class="service-desc">Gentle care for delicate fabrics</p>
                <div class="service-price">
                    <strong>₱45/kg</strong>
                    <small>(per kilogram)</small>
                </div>
                <div class="service-features">
                    <small>✓ Extra gentle</small><br>
                    <small>✓ Premium care</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Delicate Wear', 'Daily BubbleBox Laundry Hub', 45)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>
        </div>
    </section>
    <section class="shop-photos">
        <h3>Shop Photos</h3>
        <div class="photos-row">
            <a href="./images/shop1.1.jpg" class="photo-link"><img src="./images/shop1.1.jpg" alt="Shop Interior"></a>
            <a href="./images/shop1.2.jpg" class="photo-link"><img src="./images/shop1.2.jpg" alt="Machines"></a>
        </div>
    </section>
    <div class="shop-actions">
        <a href="chat.php?shop=Daily%20BubbleBox%20Laundry%20Hub"><button class="btn" id="chatBtn"><i class="fas fa-comments"></i> Chat</button></a>
        <a href="modules/cart.php"><button class="btn"><i class="fas fa-shopping-cart"></i> View Cart</button></a>
    </div>
    <?php $shop_name = 'Daily BubbleBox Laundry Hub'; include 'modules/reviews_inc.php'; ?>
</main>
<div id="imgModal" class="img-modal">
  <span class="img-modal-close" id="imgModalClose">&times;</span>
  <img class="img-modal-content" id="imgModalImg" src="" alt="Photo">
</div>
<footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Dry Zone - Cantilan</h3>
                    <p>Your directory for laundry services in Cantilan, Surigao del Sur.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="location.php">Location Map</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="about.php">About Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Poblacion, Cantilan, Surigao del Sur</li>
                        <li><i class="fas fa-phone"></i> (086) 234-5678</li>
                        <li><i class="fas fa-envelope"></i> info@dryzonecantilan.com</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Dry Zone - Cantilan. All rights reserved.</p>
            </div>
        </div>
    </footer>

<script>
// Redirect to login if trying to add to cart without authentication
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';
        if (!isLoggedIn) {
            e.preventDefault();
            window.location.href = 'login.php';
        }
    });
});
</script>

<script src="js/shop_cart.js"></script>
<script src="js/shop1.js"></script>
</body>
</html>


