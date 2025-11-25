<?php
session_start();

// If already logged in, redirect based on role
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
$errors = array();
$success = '';
$is_logged_in = isset($_SESSION['user_email']);
$is_ajax = isset($_POST['from_modal']) && $_POST['from_modal'] == '1';

if (!$is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';
    $fullname = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $passwordRepeat = $_POST["repeat_password"];
    $role = isset($_POST['role']) ? $_POST['role'] : 'customer';

    if (empty($fullname) || empty($email) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "All fields are required.";
    }
    if ($password !== $passwordRepeat) {
        $errors[] = "Passwords do not match.";
    }
    if (!in_array($role, ['customer', 'seller', 'admin'])) {
        $role = 'customer'; // Default to customer if invalid role
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered. Please log in or use another email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $insertStmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("ssss", $fullname, $email, $hashedPassword, $role);

            if ($insertStmt->execute()) {
                $userId = $insertStmt->insert_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $fullname;
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_role'] = $role;
                $success = "Registration successful! Redirecting...";
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
                $error = "An error occurred while registering. Please try again.";
            }
            $insertStmt->close();
        }
        $stmt->close();
    }
    
    // If AJAX request and there are errors, return JSON
    if ($is_ajax && (!empty($errors) || $error)) {
        header('Content-Type: application/json');
        if (!empty($errors)) {
            echo json_encode(['errors' => $errors]);
        } else {
            echo json_encode(['error' => $error]);
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Dry Zone Cantilan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/auth.css">
    <script src="js/auth.js"></script>
</head>
<body>
<div class="auth-container">
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
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join us and experience premium laundry services</p>
            </div>

            <?php if ($is_logged_in): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    You have already registered and logged in with this account.
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php foreach ($errors as $err) echo htmlspecialchars($err) . "<br>"; ?>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="post" class="modern-form" <?php if ($is_logged_in) echo 'style="pointer-events:none;opacity:0.6;"'; ?>>
                <div class="form-group floating">
                    <input type="text" id="full_name" name="full_name" placeholder=" " required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                    <label for="full_name">Full Name</label>
                    <div class="form-icon">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group floating">
                    <input type="email" id="email" name="email" placeholder=" " required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <label for="email">Email address</label>
                    <div class="form-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group floating">
                    <select id="role" name="role" required>
                        <option value="customer" <?php echo (isset($_POST['role']) && $_POST['role'] === 'customer') ? 'selected' : ''; ?>>Customer</option>
                        <option value="seller" <?php echo (isset($_POST['role']) && $_POST['role'] === 'seller') ? 'selected' : ''; ?>>Service Provider</option>
                    </select>
                    <label for="role">Account Type</label>
                    <div class="form-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                </div>

                <div class="form-group floating">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <div class="form-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-group floating">
                    <input type="password" id="repeat_password" name="repeat_password" placeholder=" " required>
                    <label for="repeat_password">Confirm Password</label>
                    <div class="form-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <button type="button" class="password-toggle" onclick="togglePassword('repeat_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-text" id="strengthText">Password strength</div>
                </div>

                <button type="submit" class="auth-btn modern-btn" name="submit">
                    <span class="btn-text">Create Account</span>
                    <div class="btn-loader">
                        <div class="loader"></div>
                    </div>
                </button>

                <div class="auth-footer">
                    Already have an account? <a href="login.php" class="auth-link">Sign in</a>
                </div>
            </form>
        </div>
    </div>

    <div class="auth-right">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = passwordInput.parentElement.querySelector('.password-toggle i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    let text = 'Password strength';
    let color = '#e2e8f0';
    
    if (password.length >= 8) strength += 25;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
    if (password.match(/\d/)) strength += 25;
    if (password.match(/[^a-zA-Z\d]/)) strength += 25;
    
    strengthFill.style.width = strength + '%';
    
    if (strength < 50) {
        color = '#ef4444';
        text = 'Weak password';
    } else if (strength < 75) {
        color = '#f59e0b';
        text = 'Medium password';
    } else {
        color = '#10b981';
        text = 'Strong password';
    }
    
    strengthFill.style.backgroundColor = color;
    strengthText.textContent = text;
    strengthText.style.color = color;
});

// Add floating label functionality
document.querySelectorAll('.floating input, .floating select').forEach(input => {
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