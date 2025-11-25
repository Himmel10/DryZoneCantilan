function addToCart(service, shop, price) {
    const isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';
    if (!isLoggedIn) {
        const modal = document.getElementById('authModal');
        if (modal) {
            modal.style.display = 'flex';
        } else {
            window.location.href = 'login.php';
        }
        return;
    }
    
    const quantity = 1;
    
    const btn = event.target.closest('.add-to-cart-btn');
    const originalText = btn.innerHTML;
    
    // Create flying item animation
    createFlyingItem(btn);
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner"></i> <span>Adding...</span>';
    btn.classList.add('loading');
    
    fetch('modules/cart_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&service=${encodeURIComponent(service)}&shop=${encodeURIComponent(shop)}&price=${price}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check"></i> <span>Added to Cart!</span>';
            btn.classList.add('success');
            
            showNotification(`✓ ${service} added to cart!`, 'success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('success', 'loading');
                updateCartCount();
            }, 2000);
        } else {
            btn.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Error</span>';
            btn.classList.add('error');
            showNotification('Error adding to cart', 'error');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('error', 'loading');
            }, 2000);
        }
    })
    .catch(error => {
        // Error handled silently
        btn.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Error</span>';
        btn.classList.add('error');
        showNotification('Error adding to cart', 'error');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            btn.classList.remove('error', 'loading');
        }, 2000);
    });
}

function createFlyingItem(btn) {
    // Create a clone of the button for animation
    const flyingItem = document.createElement('div');
    flyingItem.innerHTML = '<i class="fas fa-shopping-cart"></i>';
    flyingItem.style.cssText = `
        position: fixed;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #0A8FD3 0%, #00A8E8 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        z-index: 9999;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0, 168, 232, 0.4);
    `;
    
    // Get button position
    const btnRect = btn.getBoundingClientRect();
    flyingItem.style.left = (btnRect.left + btnRect.width / 2 - 20) + 'px';
    flyingItem.style.top = (btnRect.top + btnRect.height / 2 - 20) + 'px';
    
    document.body.appendChild(flyingItem);
    
    // Find cart icon position
    const cartIcon = document.querySelector('.nav-cart-icon');
    let cartRect = { left: window.innerWidth - 50, top: 25 };
    
    if (cartIcon) {
        cartRect = cartIcon.getBoundingClientRect();
    }
    
    // Trigger animation
    setTimeout(() => {
        flyingItem.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        flyingItem.style.left = (cartRect.left + cartRect.width / 2 - 20) + 'px';
        flyingItem.style.top = (cartRect.top + cartRect.height / 2 - 20) + 'px';
        flyingItem.style.opacity = '0.3';
        flyingItem.style.transform = 'scale(0.5)';
    }, 10);
    
    // Remove element after animation
    setTimeout(() => {
        flyingItem.remove();
    }, 800);
}

function showNotification(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? '#10b981' : '#ef4444';
    const icon = type === 'success' ? '✓' : '⚠';
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        font-weight: 600;
        z-index: 1000;
        animation: slideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-size: 0.95rem;
        max-width: 350px;
    `;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="margin-right: 8px;"></i>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

function updateCartCount() {
    // Fetch updated cart count from server
    fetch('modules/cart_handler.php?action=get_count', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartIcon = document.querySelector('.nav-cart-icon');
        if (cartIcon) {
            let badge = cartIcon.querySelector('.cart-count');
            if (data.count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'cart-count';
                    cartIcon.appendChild(badge);
                }
                badge.textContent = data.count;
            } else if (badge) {
                badge.remove();
            }
        }
    })
    .catch(error => { /* Error handled silently */ });
}

document.addEventListener('DOMContentLoaded', function() {
    const chatBtn = document.getElementById('chatBtn');
    if (chatBtn) {
        chatBtn.onclick = function() {
            alert("Chat feature coming soon!");
        };
    }

    const imgModal = document.getElementById('imgModal');
    const imgModalImg = document.getElementById('imgModalImg');
    const imgModalClose = document.getElementById('imgModalClose');

    if (imgModal) {
        document.querySelectorAll('.photo-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                imgModalImg.src = this.href;
                imgModal.classList.add('active');
            });
        });
        
        if (imgModalClose) {
            imgModalClose.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                imgModal.classList.remove('active');
                imgModalImg.src = '';
            });
        }
        
        imgModal.addEventListener('click', function(e) {
            if (e.target === imgModal || e.target === imgModalImg) {
                imgModal.classList.remove('active');
                imgModalImg.src = '';
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && imgModal.classList.contains('active')) {
                imgModal.classList.remove('active');
                imgModalImg.src = '';
            }
        });
    }
});

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px) scale(0.9);
            opacity: 0;
        }
        to {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
        to {
            transform: translateX(400px) scale(0.9);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
