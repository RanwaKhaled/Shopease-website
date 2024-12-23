<?php
    session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - ShopEase</title>
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

    <!-- Sign-In Form Section -->
    <main>
        <section class="signin-form">
            <h2>Sign In</h2>
            <form action="user_login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <!--display error message in case of incorrect credentials-->
                <?php if(isset($_GET['error'])&& $_GET['error']==1): ?>
                    <p class = "error" style = "color:red; font-size: 12px; padding-bottom: 5px;">Email or Password incorrect. Please try again.</p>
                <?php endif; ?>

                <button type="submit" name="submit">Sign In</button>
            </form>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 ShopEase. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>

</body>
</html>
