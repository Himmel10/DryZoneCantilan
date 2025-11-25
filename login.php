<?php
session_start();

if (isset($_SESSION['user_email'])) {
    $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'customer';
    if ($role === 'admin') {
        header('Location: admin/admin_dashboard.php');
        exit();
    } elseif ($role === 'seller') {
        header('Location: service_provider/serviceprovider_dashboard.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}

$error = '';
$success = '';
$is_ajax = isset($_POST['from_modal']) && $_POST['from_modal'] == '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, full_name, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $fullname, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $fullname;
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;
            $success = "Login successful! Redirecting...";
            $_POST = array();
            
            // If AJAX request, return JSON
            if ($is_ajax) {
                header('Content-Type: application/json');
                $redirectUrl = 'index.php';
                if ($role === 'admin') {
                    $redirectUrl = 'index.php';
                } elseif ($role === 'seller') {
                    $redirectUrl = 'service_provider/serviceprovider_dashboard.php';
                }
                echo json_encode(['success' => $success, 'redirect' => $redirectUrl]);
                exit();
            }
            
            // Regular form submission - redirect based on role
            if ($role === 'admin') {
                header('Location: index.php');
                exit();
            } elseif ($role === 'seller') {
                header('Location: service_provider/serviceprovider_dashboard.php');
                exit();
            } else {
                header('Location: index.php');
                exit();
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found with that email.";
    }
    
    // If AJAX request and there's an error, return JSON
    if ($is_ajax && $error) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $error]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - Dry Zone Cantilan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/auth.css">
    <script src="js/auth.js"></script>
</head>
<body>
<div class="auth-container">
    <!-- Left Side - Login Form -->
    <div class="auth-left">
        <div class="auth-form-wrapper">
            <div class="auth-brand">
                <div class="auth-brand-logo">
                    <img src="images/Dry Zone Logo.jpg" alt="Dry Zone Logo">
                </div>
                <div class="auth-brand-text">
                    <div class="auth-brand-name">Dry Zone</div>
                    <div class="auth-brand-tagline">Cantilan</div>
                </div>
            </div>

            <div class="auth-header">
                <h1 class="auth-title">Welcome back</h1>
                <p class="auth-subtitle">Sign in to your account to continue</p>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="post" autocomplete="off" class="modern-form">
                <div class="form-group floating">
                    <input type="email" id="email" name="email" placeholder=" " required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <label for="email">Email address</label>
                    <div class="form-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group floating">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <div class="form-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember" name="remember">
                        <span class="checkmark"></span>
                        Remember for 30 days
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="auth-btn modern-btn">
                    <span class="btn-text">Sign in</span>
                    <div class="btn-loader">
                        <div class="loader"></div>
                    </div>
                </button>

                <div class="auth-footer">
                    Don't have an account? <a href="register.php" class="auth-link">Sign up</a>
                </div>
                    <div class="project-note" style="margin-top: 24px; text-align: center; font-size: 0.95em; color: #555; background: #f8f8f8; border-radius: 8px; padding: 10px;">
                        <strong>Project Requirement for WEB SYSTEM 2</strong><br>
                        Created by Charlie MelLarong and Shaina Rhiz Lacharon - BSCS3A
                    </div>
            </form>
        </div>
    </div>

    <!-- Right Side - Decorative -->
    <div class="auth-right">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Add floating label functionality
document.querySelectorAll('.floating input').forEach(input => {
    input.addEventListener('focus', () => {
        input.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', () => {
        if (!input.value) {
            input.parentElement.classList.remove('focused');
        }
    });
    
    // Initialize floating labels
    if (input.value) {
        input.parentElement.classList.add('focused');
    }
});

// Form submission loader
document.querySelector('form').addEventListener('submit', function(e) {
    const btn = this.querySelector('.modern-btn');
    btn.classList.add('loading');
});
</script>
</body>
</html>