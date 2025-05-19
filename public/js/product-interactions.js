// Product card click handler
function handleProductClick(productId) {
    window.location.href = `/products/${productId}`;
}

// Add to cart functionality
function addToCart(productId) {
    const quantityInput = document.getElementById('quantity');
    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!csrfToken) {
        showNotification('CSRF token not found. Please refresh the page.', 'error');
        return;
    }

    // Show loading state
    const addToCartBtn = document.querySelector('button[onclick*="addToCart"]');
    if (addToCartBtn) {
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
        addToCartBtn.disabled = true;
    }

    // Create form data
    const formData = {
        product_id: productId,
        quantity: quantity
    };

    // Log the data being sent
    console.log('Sending data:', formData);

    fetch(window.cartRoutes.add, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            if (data.cartCount !== undefined) {
                updateCartCount(data.cartCount);
            }
            
            // Update mini cart if it exists
            if (typeof updateMiniCart === 'function') {
                updateMiniCart();
            }
        } else {
            throw new Error(data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'An error occurred while adding to cart', 'error');
    })
    .finally(() => {
        // Restore button state
        if (addToCartBtn) {
            addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Add to Cart';
            addToCartBtn.disabled = false;
        }
    });
}

// Notification helper
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());

    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white flex items-center min-w-[300px]`;
    
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Quantity handlers
function incrementQuantity(event, button) {
    event.preventDefault();
    const input = button.parentNode.querySelector('input[type="number"]');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.max);
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

function decrementQuantity(event, button) {
    event.preventDefault();
    const input = button.parentNode.querySelector('input[type="number"]');
    const currentValue = parseInt(input.value);
    const minValue = parseInt(input.min);
    
    if (currentValue > minValue) {
        input.value = currentValue - 1;
    }
}

function validateQuantity(input) {
    let value = parseInt(input.value);
    const min = parseInt(input.min);
    const max = parseInt(input.max);

    if (isNaN(value) || value < min) {
        input.value = min;
    } else if (value > max) {
        input.value = max;
    }
}

// Update cart count in header
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.fa-shopping-cart').nextElementSibling;
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Update mini cart
function updateMiniCart() {
    const cartDropdown = document.querySelector('[x-data="{ cartOpen: false }"]');
    if (cartDropdown) {
        // Toggle cart to refresh its contents
        const cartOpen = cartDropdown.__x.$data.cartOpen;
        cartDropdown.__x.$data.cartOpen = true;
        setTimeout(() => {
            cartDropdown.__x.$data.cartOpen = cartOpen;
        }, 100);
    }
} 