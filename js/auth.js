// AJAX login/register/logout/order confirmation handler for smooth UX

document.addEventListener('DOMContentLoaded', function() {
    // Modal/Login tab switching
    let isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';

    function showLoginModal() {
        const modal = document.getElementById('loginModal');
        if (modal) {
            modal.classList.add('active');
        }
        const loginBtn = document.getElementById('loginBtn');
        const logoutBtn = document.getElementById('logoutBtn');
        if (loginBtn) loginBtn.style.display = '';
        if (logoutBtn) logoutBtn.style.display = 'none';
    }

    function showPageContent() {
        const modal = document.getElementById('loginModal');
        if (modal) {
            modal.classList.remove('active');
        }
        const pageContent = document.getElementById('pageContent');
        if (pageContent) pageContent.style.display = '';
        const loginBtn = document.getElementById('loginBtn');
        const logoutBtn = document.getElementById('logoutBtn');
        if (loginBtn) loginBtn.style.display = 'none';
        if (logoutBtn) logoutBtn.style.display = '';
    }

    showPageContent();
    
    const tabLogin = document.getElementById('tabLogin');
    const tabSignup = document.getElementById('tabSignup');
    const formLogin = document.getElementById('formLogin');
    const formSignup = document.getElementById('formSignup');
    const goToSignup = document.getElementById('goToSignup');
    const goToLogin = document.getElementById('goToLogin');
    const closeModal = document.getElementById('closeModal');

    if (tabLogin && tabSignup) {
        tabLogin.addEventListener('click', function() {
            tabLogin.classList.add('active');
            tabSignup.classList.remove('active');
            formLogin.classList.add('active');
            formSignup.classList.remove('active');
        });
        tabSignup.addEventListener('click', function() {
            tabSignup.classList.add('active');
            tabLogin.classList.remove('active');
            formSignup.classList.add('active');
            formLogin.classList.remove('active');
        });
    }

    if (goToSignup) {
        goToSignup.addEventListener('click', function(e) {
            e.preventDefault();
            if (tabSignup) tabSignup.click();
        });
    }

    if (goToLogin) {
        goToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            if (tabLogin) tabLogin.click();
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function(e) {
            e.preventDefault();
            if (!isLoggedIn) {
                showLoginModal();
            }
        });
    }

    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }

    if (formSignup) {
        formSignup.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }

    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
        loginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginModal();
        });
    }

    // Login form AJAX
    const loginForm = document.querySelector('.modern-form[action="login.php"], .modern-form:not([action])');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = loginForm.querySelector('.auth-btn');
            btn.classList.add('loading');
            const formData = new FormData(loginForm);
            formData.append('from_modal', '1');
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.classList.remove('loading');
                if (data.success) {
                    showStatusMessage(data.success, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1200);
                } else if (data.error) {
                    showStatusMessage(data.error, 'error');
                }
            })
            .catch(() => {
                btn.classList.remove('loading');
                showStatusMessage('Login failed. Try again.', 'error');
            });
        });
    }

    // Register form AJAX
    const registerForm = document.querySelector('.modern-form[action="register.php"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = registerForm.querySelector('.auth-btn');
            btn.classList.add('loading');
            const formData = new FormData(registerForm);
            formData.append('from_modal', '1');
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.classList.remove('loading');
                if (data.success) {
                    showStatusMessage(data.success, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1200);
                } else if (data.errors) {
                    showStatusMessage(data.errors.join('<br>'), 'error');
                } else if (data.error) {
                    showStatusMessage(data.error, 'error');
                }
            })
            .catch(() => {
                btn.classList.remove('loading');
                showStatusMessage('Registration failed. Try again.', 'error');
            });
        });
    }

    const logoutBtn = document.getElementById('logoutBtn') || document.querySelector('.logout-btn, a[href*="logout.php"]');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            showStatusMessage('Logging out...', 'success');
            setTimeout(() => {
                window.location.href = 'logout.php';
            }, 3000);
        });
    }

    const orderForm = document.getElementById('checkoutForm') || document.querySelector('.order-form, form[action*="submit_order"]');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = orderForm.querySelector('.order-btn, button[type="submit"]');
            if (btn) btn.classList.add('loading');
            const formData = new FormData(orderForm);
            formData.append('json', '1');
            fetch('submit_order_from_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (btn) btn.classList.remove('loading');
                if (data.success) {
                    showStatusMessage(data.message || 'Order placed successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = 'customer_orders.php';
                    }, 3000);
                } else {
                    showStatusMessage(data.message || 'Order failed. Try again.', 'error');
                }
            })
            .catch(err => {
                if (btn) btn.classList.remove('loading');
                showStatusMessage('Order failed. Try again.', 'error');
            });
        });
    }
});

function showStatusMessage(msg, type) {
    const existingAlerts = document.querySelectorAll('.ajax-status-message');
    existingAlerts.forEach(alert => alert.remove());
    
    let alert = document.createElement('div');
    alert.className = 'ajax-status-message';
    alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> <span>${msg}</span>`;
    
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 16px 28px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 99999;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        animation: slideDown 0.3s ease-out;
        ${type === 'success' ? 'background: #10b981; color: white;' : 'background: #ef4444; color: white;'}
        min-width: 300px;
        text-align: center;
        justify-content: center;
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => alert.remove(), 300);
    }, 2000);
}

if (!document.querySelector('style[data-ajax-animations]')) {
    const style = document.createElement('style');
    style.setAttribute('data-ajax-animations', 'true');
    style.textContent = `
        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}
