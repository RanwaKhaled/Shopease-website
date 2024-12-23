<?php
    include("db_connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ShopEase</title>
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

    <!-- Sign-Up Form Section -->
    <main>
        <section class="signup-form">
            <h2>Create an Account</h2>
            <form action="user_signup.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <!--display message if the user already has an account-->
                <?php if(isset($_GET['error'])&& $_GET['error']==1): ?>
                    <p class = "error" style = "color:#333333; font-size: 14px; padding-bottom: 5px;">User already exists. <a href="signin.php" class="log_in_link" style="text-decoration: underline">Log in</a></p>
                <?php endif; ?>

                <button type="submit" name="signup">Sign Up</button>
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
