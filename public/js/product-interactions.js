// Product card click handler
function handleProductClick(productId) {
    window.location.href = `/products/${productId}`;
}

// Add to cart functionality
function addToCart(productId) {
    console.log('addToCart function called with productId:', productId);
    
    // Check if cartRoutes is defined
    if (!window.cartRoutes || !window.cartRoutes.add) {
        console.error('cartRoutes not defined:', window.cartRoutes);
        showNotification('Error: Cart routes not properly initialized. Please refresh the page.', 'error');
        return;
    }
    
    // Get the quantity
    const quantityInput = document.getElementById('quantity');
    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
    console.log('Quantity:', quantity);

    // Get the CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('CSRF Token:', token ? 'Found' : 'Not found');

    if (!token) {
        showNotification('Error: CSRF token not found. Please refresh the page and try again.', 'error');
        return;
    }

    // Get the button and show loading state
    const addToCartBtn = document.querySelector(`button[onclick*="addToCart(${productId})"]`);
    console.log('Found button:', addToCartBtn);
    
    if (addToCartBtn) {
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
    }

    // Create form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('_token', token);

    console.log('Sending request to:', window.cartRoutes.add);
    console.log('Request data:', {
        product_id: productId,
        quantity: quantity,
        _token: token
    });

    // Send the request
    fetch(window.cartRoutes.add, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification(data.message || 'Product added to cart successfully!', 'success');
            updateCartCount(data.cartCount);
            updateMiniCart(data.cartItems);
        } else {
            showNotification(data.message || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showNotification(`Error: ${error.message}. Please try again or contact support if the problem persists.`, 'error');
    })
    .finally(() => {
        // Restore button state
        if (addToCartBtn) {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Add to Cart';
        }
    });
}

// Enhanced notification helper
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());

    // Create new notification
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed bottom-4 right-4 p-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white text-sm transform transition-all duration-300 ease-in-out`;
    
    // Add slide-in animation
    notification.style.transform = 'translateY(100%)';
    notification.style.opacity = '0';
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Trigger animation
    requestAnimationFrame(() => {
        notification.style.transform = 'translateY(0)';
        notification.style.opacity = '1';
    });
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateY(100%)';
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 300);
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
function updateMiniCart(cartItems) {
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