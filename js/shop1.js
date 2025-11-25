// Shop 1 - Daily BubbleBox Laundry Hub JavaScript
// This file contains shop-specific functionality

document.addEventListener('DOMContentLoaded', function() {
    // Initialize shop features
    initializeShopFeatures();
    
    // Update cart count on page load
    updateCartCount();
    
    // Initialize image lightbox
    initializeImageLightbox();
});

/**
 * Initialize image lightbox modal
 */
function initializeImageLightbox() {
    const imgModal = document.getElementById('imgModal');
    const imgModalImg = document.getElementById('imgModalImg');
    const imgModalClose = document.getElementById('imgModalClose');

    if (!imgModal || !imgModalImg) return;

    document.querySelectorAll('.photo-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            imgModalImg.src = this.href;
            imgModal.style.display = "flex";
        });
    });

    if (imgModalClose) {
        imgModalClose.onclick = function() {
            imgModal.style.display = "none";
            imgModalImg.src = '';
        };
    }

    imgModal.onclick = function(e) {
        if (e.target === imgModal) {
            imgModal.style.display = "none";
            imgModalImg.src = '';
        }
    };
}

/**
 * Initialize shop-specific features
 */
function initializeShopFeatures() {
    // Add any shop-specific event listeners here
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

/**
 * Update cart count display
 */
function updateCartCount() {
    fetch('modules/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartIcon = document.querySelector('.nav-cart-icon .cart-count');
            if (cartIcon) {
                if (data.count > 0) {
                    cartIcon.textContent = data.count;
                    cartIcon.style.display = 'flex';
                } else {
                    cartIcon.style.display = 'none';
                }
            }
        })
        .catch(err => {
            // Error updating cart - continue silently
        });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

