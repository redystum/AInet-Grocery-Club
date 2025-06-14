import './bootstrap';

// Check if product is in wishlist and update icon
window.checkWishlistStatus = function() {
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        const productId = button.dataset.productId;
        const icon = button.querySelector('.wishlist-icon');

        fetch(`/wishlist/check/${productId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.in_wishlist) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                button.classList.remove('opacity-0');
                button.classList.add('opacity-100');
            }
        })
        .catch(error => console.error('Error checking wishlist status:', error));
    });
};

// Toggle wishlist status
window.toggleWishlist = function(productId, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const buttons = document.querySelectorAll(`.wishlist-btn[data-product-id="${productId}"]`);

    fetch(`/wishlist/toggle/${productId}`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            buttons.forEach(button => {
                const icon = button.querySelector('.wishlist-icon');

                if (data.in_wishlist) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            });

            // If product was removed from wishlist, dispatch event to update Livewire components
            if (!data.in_wishlist) {
                // Use Livewire's dispatch method if available in the current context
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('productRemovedFromWishlist');
                }
            }

            window.showToast(data.message, 'success');
        } else {
            window.showToast('Failed to update wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast('An error occurred while updating wishlist', 'error');
    });
};

// Setup for checking wishlist status when page loads
window.setupWishlistStatus = function() {

    // Check wishlist status for all products
    checkWishlistStatus();
};

// Global toast notification functionality
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');

    // Set classes based on type
    const baseClasses = 'flex items-center p-4 mb-3 rounded-lg shadow-md transition-all duration-300 transform translate-x-full';
    const typeClasses = type === 'success'
        ? 'bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-200'
        : 'bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-200';

    toast.className = `${baseClasses} ${typeClasses}`;
    toast.innerHTML = `
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg me-3 ${type === 'success' ? 'bg-green-100 dark:bg-green-800 text-green-500 dark:text-green-200' : 'bg-red-100 dark:bg-red-800 text-red-500 dark:text-red-200'}">
            <i class="fas ${type === 'success' ? 'fa-check' : 'fa-times'}"></i>
        </div>
        <div>${message}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-times"></i>
        </button>
    `;

    // Add toast to container
    container.appendChild(toast);

    // Animate entrance
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
    }, 10);

    // Setup close button
    const closeBtn = toast.querySelector('button');
    closeBtn.addEventListener('click', () => {
        removeToast(toast);
    });

    // Auto close after 5 seconds
    setTimeout(() => {
        removeToast(toast);
    }, 5000);
};

window.removeToast = function(toast) {
    toast.classList.remove('translate-x-0');
    toast.classList.add('translate-x-full');

    setTimeout(() => {
        toast.remove();
    }, 300);
};

// Global function to quickly add products to cart
window.quickAddToCart = function(productId, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const button = event ? event.currentTarget : null;
    const originalHTML = button ? button.innerHTML : null;

    // Show loading state if button exists
    if (button) {
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
    }

    fetch(`/cart/add/${productId}?quantity=1`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast(`${data.message} (${data.quantity}x)`, 'success');
        } else {
            window.showToast('Failed to add product to cart', 'error');
        }

        // Reset button state if button exists
        if (button) {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast('An error occurred while adding to cart', 'error');

        // Reset button state if button exists
        if (button) {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    });
};
