<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_email']);
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

// Determine the base path for navigation links
$script_path = $_SERVER['SCRIPT_NAME'];
$base_path = (strpos($script_path, '/modules/') !== false) ? '../' : '';
?>
<header>
    <div class="container container-header header-row">
        <!-- Logo - Hidden on Mobile, Visible on Desktop -->
        <div class="logo" style="margin-left:24px;">
            <a href="<?php echo $base_path; ?>index.php" class="photo-link" id="shopLogoLink">
                <img src="<?php echo $base_path; ?>images/Dry Zone Logo.jpg" alt="Dry Zone Cantilan Logo" style="height:64px;width:auto;margin-right:12px;" id="shopLogo">
            </a>
            <h1>Dry Zone - Cantilan</h1>
        </div>
        
        <!-- Desktop Navigation -->
        <div class="nav-flex" id="navFlex">
            <ul class="nav-links">
                <li><a href="<?php echo $base_path; ?>index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="<?php echo $base_path; ?>location.php"><i class="fas fa-map-marked-alt"></i> Location Map</a></li>
                <li><a href="<?php echo $base_path; ?>services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
                <li><a href="<?php echo $base_path; ?>about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                <?php if ($is_logged_in): ?>
                    <?php if ($user_role === 'seller'): ?>
                        <li><a href="<?php echo $base_path; ?>service_provider/serviceprovider_dashboard.php"><i class="fas fa-store"></i> Dashboard</a></li>
                    <?php elseif ($user_role === 'admin'): ?>
                        <li><a href="<?php echo $base_path; ?>admin/admin_dashboard.php"><i class="fas fa-cog"></i> Admin Panel</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <?php if ($is_logged_in): ?>
                <?php if ($user_role !== 'seller'): ?>
                <a href="<?php echo $base_path; ?>modules/cart.php" class="nav-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                <div class="user-profile-dropdown">
                    <button class="profile-trigger" id="profileTrigger">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($user_name); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="profile-dropdown-menu" id="profileDropdown">
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                                <div class="profile-email"><?php echo htmlspecialchars($user_email); ?></div>
                                <div class="profile-role">
                                    <span class="role-badge role-<?php echo $user_role; ?>"><?php echo ucfirst($user_role); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="profile-menu-divider"></div>
                        <?php if ($user_role === 'customer'): ?>
                        <a href="<?php echo $base_path; ?>customer_orders.php" class="profile-menu-item">
                            <i class="fas fa-shopping-bag"></i>
                            <span>My Orders</span>
                        </a>
                        <?php elseif ($user_role === 'seller'): ?>
                        <a href="<?php echo $base_path; ?>service_provider/serviceprovider_dashboard.php" class="profile-menu-item">
                            <i class="fas fa-store"></i>
                            <span>My Shop Details</span>
                        </a>
                        <?php elseif ($user_role === 'admin'): ?>
                        <a href="<?php echo $base_path; ?>admin/admin_dashboard.php" class="profile-menu-item">
                            <i class="fas fa-cog"></i>
                            <span>Admin Panel</span>
                        </a>
                        <?php endif; ?>
                        <div class="profile-menu-divider"></div>
                        <a href="<?php echo $base_path; ?>logout.php" class="profile-menu-item logout-item" id="logoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="login.php" class="auth-btn login-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="register.php" class="auth-btn register-btn"><i class="fas fa-user-plus"></i> Register</a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Mobile Header Section -->
        <div class="mobile-header-section">
            <!-- Profile Icon (Left) -->
            <div class="mobile-profile-icon">
                <?php if ($is_logged_in): ?>
                    <button class="mobile-profile-btn" id="mobileProfileBtn">
                        <i class="fas fa-user-circle"></i>
                    </button>
                <?php else: ?>
                    <a href="login.php" class="mobile-profile-btn">
                        <i class="fas fa-user"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Search Bar (Middle) -->
            <div class="mobile-search-section">
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Search...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            
            <!-- Cart Icon (Right) -->
            <div class="mobile-cart-section">
                <?php if ($is_logged_in && $user_role !== 'seller'): ?>
                <a href="<?php echo $base_path; ?>modules/cart.php" class="mobile-cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Hamburger Menu Button -->
        <button class="hamburger-menu" id="hamburgerBtn">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
<script>
// Header functionality
document.addEventListener('DOMContentLoaded', function() {
    const profileTrigger = document.getElementById('profileTrigger');
    const userProfileDropdown = document.querySelector('.user-profile-dropdown');
    const logoutBtn = document.getElementById('logoutBtn');
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navFlex = document.getElementById('navFlex');
    
    // Hamburger menu toggle
    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            navFlex.classList.toggle('active');
            hamburgerBtn.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = navFlex.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navFlex.classList.remove('active');
                hamburgerBtn.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navFlex.contains(e.target) && !hamburgerBtn.contains(e.target)) {
                navFlex.classList.remove('active');
                hamburgerBtn.classList.remove('active');
            }
        });
    }
    
    // Profile dropdown toggle
    if (profileTrigger && userProfileDropdown) {
        profileTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            userProfileDropdown.classList.toggle('active');
        });
        
        document.addEventListener('click', function(e) {
            const mobileBtn = document.getElementById('mobileProfileBtn');
            if (!userProfileDropdown.contains(e.target) && (mobileBtn && !mobileBtn.contains(e.target))) {
                userProfileDropdown.classList.remove('active');
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userProfileDropdown.classList.remove('active');
            }
        });
    }

    // Handle logout button click
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            if (userProfileDropdown) {
                userProfileDropdown.classList.remove('active');
            }
        });
    }
    
    // Mobile profile button handler
    const mobileProfileBtn = document.getElementById('mobileProfileBtn');
    if (mobileProfileBtn && userProfileDropdown) {
        mobileProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            userProfileDropdown.classList.toggle('active');
        });
    }
    
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const searchIcon = document.querySelector('.search-icon');
    
    if (searchInput && searchIcon) {
        // Search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        // Search on icon click
        searchIcon.addEventListener('click', function(e) {
            e.preventDefault();
            performSearch();
        });
    }
    
    function performSearch() {
        const searchInput = document.querySelector('.search-input');
        const query = searchInput ? searchInput.value.trim() : '';
        
        if (query) {
            window.location.href = 'search.php?q=' + encodeURIComponent(query);
        } else {
            window.location.href = 'search.php';
        }
    }
});
</script>
<script src="<?php echo $base_path; ?>js/auth.js"></script>

