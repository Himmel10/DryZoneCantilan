<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Zone - Cantilan | Local Laundry Shops Directory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php include 'header.php'; ?>
<div id="pageContent">
    <!-- Hero Section with CTA -->
    <section class="hero">
        <div class="container">
            <div style="max-width: 600px; margin: 0 auto; text-align: center;">
                <h1 style="font-size: 2.2rem; margin-bottom: 15px; color: white; font-weight: 800; letter-spacing: -0.5px;">Welcome to Dry Zone Cantilan</h1>
                <p style="font-size: 1rem; color: rgba(255,255,255,0.93); margin-bottom: 24px; line-height: 1.6; font-weight: 500;">Your trusted partner for professional laundry services. Find the perfect shop, book your service, and enjoy clean clothes delivered to your door.</p>
                <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;">
                    <a href="#shops" class="btn" style="background: white; color: var(--primary); padding: 10px 24px; font-size: 0.93rem; font-weight: 600; box-shadow: 0 2px 8px rgba(255,255,255,0.2);">
                        <i class="fas fa-arrow-down"></i> Explore Shops
                    </a>
                    <a href="services.php" class="btn" style="background: rgba(255,255,255,0.15); color: white; padding: 10px 24px; font-size: 0.93rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                        <i class="fas fa-list"></i> View Services
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Shop Section -->
    <section style="background: #ffffff; padding: 40px 20px; border-bottom: 1px solid #e2e8f0;">
        <div class="container" style="max-width: 700px;">
            <h2 style="text-align: center; font-size: 1.5rem; color: var(--dark); margin-bottom: 24px; font-weight: 800; letter-spacing: -0.5px;">Find Your Perfect Laundry Shop</h2>
            <div style="display: flex; gap: 10px;">
                <a href="search.php" class="btn-primary-hero" style="flex: 1;">
                    <i class="fas fa-search"></i> Find a Shop Now
                </a>
                <a href="location.php" class="btn-secondary-hero" style="flex: 1;">
                    <i class="fas fa-map-marked-alt"></i> View Map
                </a>
            </div>
        </div>
    </section>
    <!-- Featured Shops Section -->
    <section class="container" id="shops" style="padding: 60px 20px;">
        <h2 style="text-align: center; font-size: 2rem; color: var(--primary); margin-bottom: 50px;">Featured Laundry Shops</h2>
        <div class="shops">
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/bubblebox.png" class="photo-link">
                        <img src="./images/bubblebox.png" alt="Daily BubbleBox Laundry Hub">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.8</div>
                </div>
                <div class="shop-content">
                    <h3>Daily BubbleBox Laundry Hub</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Poblacion, Cantilan</span>
                    </div>
                    <p>Complete laundry services with free pickup and delivery within Poblacion area.</p>
                    <a href="shop1.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/Lava.jpg" class="photo-link">
                        <img src="./images/Lava.jpg" alt="Lava'z Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.5</div>
                </div>
                <div class="shop-content">
                    <h3>Lava'z Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>P-4, Falcon St., Magosilom, Cantilan</span>
                    </div>
                    <p>Offering wash, dry, and fold services with eco-friendly detergents.</p>
                    <a href="shop2.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/fluff.png" class="photo-link">
                        <img src="./images/fluff.png" alt="Fluff'n Fold Express Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.7</div>
                </div>
                <div class="shop-content">
                    <h3>Fluff'n Fold Express Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Purok-2 magosilom, Cantilan</span>
                    </div>
                    <p>Specializing in dry cleaning and delicate fabric care.</p>
                    <a href="shop3.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/Methsuliah.png" class="photo-link">
                        <img src="./images/Methsuliah.png" alt="Methusilah's Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.3</div>
                </div>
                <div class="shop-content">
                    <h3>Methusilah's Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Purok-5, Sitio Tapa, Brgy. San Pedro, Cantilan</span>
                    </div>
                    <p>Express laundry service with 3-hour turnaround available.</p>
                    <a href="shop4.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/EP.jpg" class="photo-link">
                        <img src="./images/EP.jpg" alt="EP Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.6</div>
                </div>
                <div class="shop-content">
                    <h3>EP Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Urbiztondo St., Purok 3 Magosilom, Cantilan</span>
                    </div>
                    <p>Professional washing and ironing services with pickup available.</p>
                    <a href="shop5.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/Frankie.png" class="photo-link">
                        <img src="./images/Frankie.png" alt="Frankie Laundry Shop">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.9</div>
                </div>
                <div class="shop-content">
                    <h3>Frankie Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Orillaneda St., Purok-3, Lininti-an Cantilan</span>
                    </div>
                    <p>Environmentally friendly laundry using hypoallergenic detergents.</p>
                    <a href="shop6.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/wash.png" class="photo-link">
                        <img src="./images/wash.png" alt="Eco Clean Laundry">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.4</div>
                </div>
                <div class="shop-content">
                    <h3>Wash & Shine Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Magosilom, Cantilan</span>
                    </div>
                    <p>Your affordable laundry shop in Cantilan.</p>
                    <a href="shop7.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/washerman.png" class="photo-link">
                        <img src="./images/washerman.png" alt="Eco Clean Laundry">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.6</div>
                </div>
                <div class="shop-content">
                    <h3>Washerman Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Pag-antayan, Cantilan</span>
                    </div>
                    <p>Experience the ultimate convenience with Washerman Laundry Shop! We offer fast, reliable wash-and-fold services designed to save you time and hassle.</p>
                    <a href="shop8.php" class="btn">View Details</a>
                </div>
            </div>
            <div class="shop-card">
                <div class="shop-img">
                    <a href="./images/everybody.png" class="photo-link">
                        <img src="./images/everybody.png" alt="Eco Clean Laundry">
                    </a>
                    <div class="rating-badge"><i class="fas fa-star"></i> 4.1</div>
                </div>
                <div class="shop-content">
                    <h3>Everybody Laundry Shop</h3>
                    <div class="shop-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Calagdaan, Cantilan</span>
                    </div>
                    <p>From delicate garments to everyday wear, our professional team provides meticulous washing, drying, and expert folding, using premium detergents and conditioners.</p>
                    <a href="shop9.php" class="btn">View Details</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Chat & Support Section -->
    <section style="background: #ffffff; padding: 50px 20px; border-top: 1px solid #e2e8f0;">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
                <div>
                    <h2 style="font-size: 1.8rem; color: var(--dark); margin-bottom: 16px; font-weight: 800; letter-spacing: -0.5px;">Connect with Shop Owners</h2>
                    <p style="font-size: 0.95rem; color: var(--medium); margin-bottom: 20px; line-height: 1.6; font-weight: 500;">Have questions about our services? Chat directly with shop owners to get instant answers about pricing, availability, and special services.</p>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 12px; display: flex; gap: 10px;">
                            <i class="fas fa-check" style="color: var(--primary); font-weight: bold;"></i>
                            <span style="color: var(--mid-dark); font-weight: 500;">Real-time messaging with shop owners</span>
                        </li>
                        <li style="margin-bottom: 12px; display: flex; gap: 10px;">
                            <i class="fas fa-check" style="color: var(--primary); font-weight: bold;"></i>
                            <span style="color: var(--mid-dark); font-weight: 500;">Quick responses to your inquiries</span>
                        </li>
                        <li style="margin-bottom: 12px; display: flex; gap: 10px;">
                            <i class="fas fa-check" style="color: var(--primary); font-weight: bold;"></i>
                            <span style="color: var(--mid-dark); font-weight: 500;">Get personalized service recommendations</span>
                        </li>
                        <li style="margin-bottom: 12px; display: flex; gap: 10px;">
                            <i class="fas fa-check" style="color: var(--primary); font-weight: bold;"></i>
                            <span style="color: var(--mid-dark); font-weight: 500;">Available 24/7 for your convenience</span>
                        </li>
                    </ul>
                </div>
                <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 30px; border-radius: 12px; border: 1px solid #cffafe;">
                    <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <div style="width: 32px; height: 32px; background: var(--gradient-2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">👤</div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; font-size: 0.9rem; color: var(--dark);">Shop Owner</div>
                                <div style="font-size: 0.8rem; color: var(--medium);">2:45 PM</div>
                            </div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 12px; border-radius: 8px; font-size: 0.9rem; color: var(--dark); border-left: 3px solid var(--primary);">Hi! How can I help you today?</div>
                    </div>
                    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px; justify-content: flex-end;">
                            <div style="text-align: right; flex: 1;">
                                <div style="font-weight: 700; font-size: 0.9rem; color: var(--dark);">You</div>
                                <div style="font-size: 0.8rem; color: var(--medium);">2:47 PM</div>
                            </div>
                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">👤</div>
                        </div>
                        <div style="background: var(--gradient-2); padding: 10px 12px; border-radius: 8px; font-size: 0.9rem; color: white; margin-left: auto; width: fit-content;">What are your opening hours?</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section style="background: var(--gradient-2); padding: 60px 20px; text-align: center;">
        <div class="container">
            <h2 style="font-size: 1.8rem; color: white; margin-bottom: 16px; font-weight: 800; letter-spacing: -0.5px;">Ready to Experience Quality Laundry?</h2>
            <p style="font-size: 0.95rem; color: rgba(255,255,255,0.93); margin-bottom: 24px; max-width: 600px; margin-left: auto; margin-right: auto; font-weight: 500;">Choose from our network of trusted laundry shops and get your clothes clean in no time. Book now and enjoy convenient laundry services.</p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;">
                <a href="#shops" style="background: white; color: var(--primary); padding: 10px 24px; font-size: 0.9rem; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(255,255,255,0.2);">
                    <i class="fas fa-store"></i> Browse Shops
                </a>
                <a href="services.php" style="background: rgba(255,255,255,0.15); color: white; padding: 10px 24px; font-size: 0.9rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; backdrop-filter: blur(10px);">
                    <i class="fas fa-concierge-bell"></i> View Services
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->

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
</div>
<div id="imgModal" class="img-modal" style="display:none; position:fixed; z-index:9999; left:0;top:0;width:100vw;height:100vh;background: rgba(44,62,80,0.85);align-items:center;justify-content:center;">
    <span class="img-modal-close" id="imgModalClose" style="position:absolute;top:40px;right:60px;font-size:3rem;color:#fff;cursor:pointer;z-index:10001;">&times;</span>
    <img class="img-modal-content" id="imgModalImg" src="" alt="Photo" style="max-width:90vw;max-height:80vh;display:block;margin:auto;box-shadow:0 8px 40px rgba(0,0,0,0.5);border-radius:16px;">
</div>

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
</body>
</html>



