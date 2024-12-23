<?php
session_start();
include("db_connection.php");
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - ShopEase</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
    <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <!-- Dropdown Menu for Products -->
                <li class="dropdown">
                    <a href="products.php">Products</a>
                    <div class="dropdown-content">
                        <a href="mens-products.php">Men's Products</a>
                        <a href="womens-products.php">Women's Products</a>
                        <a href="accessories.php">Accessories</a>
                    </div>
                </li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="signup.php">Sign Up</a></li>
                <li><a href="signin.php">Sign In</a></li>
                <li><a href="cart.php">Cart (<span id="cart-count">0</span>)</a></li>
                <li><a href="account.php">Account</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="cart">
            <h2>Your Cart</h2>
            <div id="cart-items"></div>
            <div id="cart-total"></div>
            
            <!-- Hidden input to pass login status to JavaScript -->
            <input type="hidden" id="isLoggedIn" value="<?= $isLoggedIn ? 'true' : 'false' ?>">
            
            <button id="checkout-button">Proceed to Checkout</button>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 ShopEase. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalContainer = document.getElementById('cart-total');
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // If the cart is empty, show a message
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
                cartTotalContainer.innerHTML = '';
            } else {
                let total = 0;
                cartItemsContainer.innerHTML = cart.map(item => {
                    total += parseFloat(item.price.replace('$', '')) * item.quantity;  // Account for quantity
                    return `
                        <div class="cart-item">
                            <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                            <div class="cart-item-details">
                                <p>${item.name}</p>
                                <p>${item.price}</p>
                                <p>Size: 
                                    <select class="cart-size-dropdown" data-name="${item.name}">
                                        <option value="S" ${item.size === 'S' ? 'selected' : ''}>S</option>
                                        <option value="M" ${item.size === 'M' ? 'selected' : ''}>M</option>
                                        <option value="L" ${item.size === 'L' ? 'selected' : ''}>L</option>
                                    </select>
                                </p>
                                <p>Quantity: 
                                    <button class="decrease-quantity" data-name="${item.name}">-</button>
                                    ${item.quantity}
                                    <button class="increase-quantity" data-name="${item.name}">+</button>
                                </p>
                                <button class="remove-item" data-name="${item.name}">Remove</button>
                            </div>
                        </div>
                    `;
                }).join('');

                cartTotalContainer.innerHTML = `<p>Total: $${total.toFixed(2)}</p>`;
            }

            // Update cart count in header
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = cart.length;

            // Event listener for increasing quantity
            const increaseButtons = document.querySelectorAll('.increase-quantity');
            increaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productName = this.getAttribute('data-name');
                    let product = cart.find(item => item.name === productName);
                    fetch(`check_stock.php?product_name=${productName}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const currentStock = data.stock;
                                if (currentStock > 0) {
                                    // Decrease stock in the database and update cart
                                    fetch('update_cart.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: `product_name=${productName}&action=increase`,
                                    })
                                    .then(() => {
                                        product.quantity += 1;
                                        localStorage.setItem('cart', JSON.stringify(cart));
                                        location.reload();
                                    });
                                } else {
                                    alert('Out of stock!');
                                }
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });

            // Event listener for decreasing quantity
            const decreaseButtons = document.querySelectorAll('.decrease-quantity');
            decreaseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productName = this.getAttribute('data-name');
                    let product = cart.find(item => item.name === productName);
                    if (product.quantity > 1) {
                        // Increase stock in the database and update cart
                        fetch('update_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `product_name=${productName}&action=decrease`,
                        })
                        .then(() => {
                            product.quantity -= 1;
                            localStorage.setItem('cart', JSON.stringify(cart));
                            location.reload();
                        });
                    }
                });
            });

            // Event listener for removing items from cart
            const removeButtons = document.querySelectorAll('.remove-item');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productName = this.getAttribute('data-name');
                    let product = cart.find(item => item.name === productName);
                    const productQuantity = product.quantity;
                    
                    // Restore the stock to the database before removing from the cart
                    fetch('update_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `product_name=${productName}&action=remove&current_quantity=${productQuantity}`,
                    })
                    .then(() => {
                        cart = cart.filter(item => item.name !== productName);
                        localStorage.setItem('cart', JSON.stringify(cart));
                        location.reload();
                    });
                });
            });

            // Event listener for changing size
            const sizeDropdowns = document.querySelectorAll('.cart-size-dropdown');
            sizeDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', function() {
                    const selectedSize = this.value;
                    const productName = this.getAttribute('data-name');

                    // Update the cart with the new size
                    cart = cart.map(item => {
                        if (item.name === productName) {
                            item.size = selectedSize;
                        }
                        return item;
                    });

                    localStorage.setItem('cart', JSON.stringify(cart));
                    location.reload();
                });
            });

            // Handle checkout button click
            const checkoutButton = document.getElementById('checkout-button');
            if (checkoutButton) {
                checkoutButton.addEventListener('click', function () {
                    const isLoggedIn =
                        document.getElementById('isLoggedIn').value === 'true';

                    if (!isLoggedIn) {
                        alert('You must be logged in to proceed with checkout.');
                        return;
                    }

                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
                    if (cart.length === 0) {
                        alert('Your cart is empty.');
                        return;
                    }

                    // Clear the cart and display confirmation
                    localStorage.removeItem('cart');

                    // Clear the cart items and update the cart total on the page
                    cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
                    cartTotalContainer.innerHTML = '';

                    // Update the cart count in the header
                    const cartCount = document.getElementById('cart-count');
                    cartCount.textContent = '0';

                    alert('Thank you for your purchase!');
                    updateCartCount();
                });
            }
        });

    </script>
</body>
</html>
