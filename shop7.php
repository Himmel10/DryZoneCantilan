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
    <title>Everybody Laundry Shop | Dry Zone Cantilan</title>
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
        <a href="./images/wash.png" class="photo-link">
            <img src="./images/wash.png" alt="Everybody Laundry Shop" class="shop-detail-img">
        </a>
        <div class="shop-detail-info">
            <h2>Everybody Laundry Shop</h2>
            <div class="shop-info"><i class="fas fa-map-marker-alt"></i> Pag-antayan, Cantilan</div>
            <div class="shop-info"><i class="fas fa-star rating"></i> 4.4 (65 reviews)</div>
            <div class="shop-desc">
                <p>Everybody’s favorite laundry shop! Friendly staff and great results every time.</p>
            </div>
            <div class="shop-extra">
                <span><b>Daily Orders:</b> ~22 customers</span>
            </div>
            <div class="shop-message">
                <button class="message-btn" onclick="window.location.href='chat.php?shop=Everybody Laundry Shop'">
                    <i class="fas fa-envelope"></i> Message Shop
                </button>
            </div>
        </div>
    </div>
    <section class="shop-services">
        <h3>Services & Pricing</h3>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <h4>Wash & Fold</h4>
                <p class="service-desc">Standard clothes (min 5kg, max 8kg)</p>
                <div class="service-price">
                    <strong>₱33</strong>
                    <small>(/kg)</small>
                </div>
                <div class="service-features">
                    <small>✓ Professional washing</small><br>
                    <small>✓ Expert folding</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Wash & Fold', 'Everybody Laundry Shop', 33)">
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
                    <strong>₱160/item</strong>
                    <small>(per piece)</small>
                </div>
                <div class="service-features">
                    <small>✓ Delicate care</small><br>
                    <small>✓ Professional finish</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Dry Cleaning', 'Wash & Shine Laundry Shop', 160)">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h4>Mattress Cleaning</h4>
                <p class="service-desc">Foam, blankets, comforters</p>
                <div class="service-price">
                    <strong>₱250</strong>
                    <small>(/mattress)</small>
                </div>
                <div class="service-features">
                    <small>✓ Deep cleaning</small><br>
                    <small>✓ Stain removal</small>
                </div>
                <button class="add-to-cart-btn" onclick="addToCart('Mattress Cleaning', 'Everybody Laundry Shop', 250)">
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
                <button class="add-to-cart-btn" onclick="addToCart('Ironing', 'Wash & Shine Laundry Shop', 30)">
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
        <a href="chat.php?shop=Everybody%20Laundry%20Shop"><button class="btn" id="chatBtn"><i class="fas fa-comments"></i> Chat</button></a>
        <a href="modules/cart.php"><button class="btn"><i class="fas fa-shopping-cart"></i> View Cart</button></a>
    </div>
    <?php $shop_name = 'Wash & Shine Laundry Shop'; include 'modules/reviews_inc.php'; ?>
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










