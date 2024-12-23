document.addEventListener('DOMContentLoaded', function() {
    // Function to update cart count in header
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartCount = document.getElementById('cart-count');
        if (cartCount) { // Ensure the cart count element exists
            cartCount.textContent = cart.length;  // Update cart count
        }
    }

    // Call the updateCartCount function on every page load
    updateCartCount();

    // If the page contains product cards with buttons, add the event listener for adding to cart
    const buttons = document.querySelectorAll('.product-card button');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = button.getAttribute('data-product-id');
            const productStock = parseInt(this.getAttribute('data-stock'), 10);
            const productCard = this.closest('.product-card');
            const productName = productCard.querySelector('h3').textContent;
            const productPrice = productCard.querySelector('p').textContent;
            const productImage = productCard.querySelector('img').src;
            const sizeDropdown = productCard.querySelector('.size-dropdown');
            const selectedSize = sizeDropdown.value;

            // Check if a size is selected before adding to the cart
            if (!selectedSize) {
                alert('Please select a size before adding to the cart.');
                return;  // Stop the function if no size is selected
            }

            // Send AJAX request to decrease stock in the database
        fetch('update_stock.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`,
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    let newStock = data.new_stock;

                    // Update button UI if stock reaches zero
                    if (newStock === 0) {
                        button.textContent = 'Out of Stock';
                        button.classList.remove('add-to-cart');
                        button.classList.add('out-of-stock');
                        button.disabled = true;
                        button.style.backgroundColor = 'rgba(130, 128, 128, 0.81)';
                    } else {
                        button.setAttribute('data-stock', newStock);
                    }

                    // Add product to cart in localStorage
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
                    const existingProduct = cart.find(item => item.name === productName && item.size === selectedSize);

                    if (existingProduct) {
                        existingProduct.quantity += 1;
                    } else {
                        cart.push({
                            name: productName,
                            price: productPrice,
                            image: productImage,
                            quantity: 1,
                            size: selectedSize,
                        });
                    }
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartCount();
                    alert('Item added to cart!');
                } else if (data.status === 'out_of_stock') {
                    alert('Sorry, this product is out of stock.');
                    button.textContent = 'Out of Stock';
                    button.disabled = true;
                } else {
                    alert('An error occurred. Please try again.');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
