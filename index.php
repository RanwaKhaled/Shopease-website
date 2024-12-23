<?php
    // connect to database
    include("db_connection.php");
    // fetch products from men's category
    $sql = "SELECT * FROM products WHERE price > 30";
    $result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEase - Fashion Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header Section with Dropdown Menu -->
    <header>
        <<nav>
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

    <!-- Main Content Section -->
    <main>
        <section class="hero">
            <h1>Welcome to ShopEase</h1>
            <p>Your one-stop shop for the latest fashion trends!</p>
            <a href="products.php" class="cta-button">Shop Now</a>
        </section>

        <!-- Featured Products Section -->
        <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="product-grid">
            <?php
                if (mysqli_num_rows($result)>0){
                    // loop through products to display them 
                    while($row = mysqli_fetch_assoc($result)){
                        $product_stock = $row['quantity']; // Get stock quantity
                        
                        // Check if stock is available
                        if ($product_stock > 0) {
                            $add_to_cart_button = '<button class="add-to-cart" data-product-id="'.$row['product_id'].'" data-stock="'.$product_stock.'">Add to Cart</button>';
                        } else {
                            $stock_message = "Out of Stock";
                            $add_to_cart_button = '<button class="out-of-stock" style="background-color: rgba(130, 128, 128, 0.81);" disabled>Out of Stock</button>';
                        }
                        echo '
                <div class="product-card">
                    <img src="'.$row['image'].'" alt="'.$row['name'].'">
                    <h3>'.$row['name'].'</h3>
                    <p>$'. number_format($row['price'], 2).'</p>
                    <select class="size-dropdown">
                        <option value="" disabled selected>Select Size</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                    </select>
                    '.$add_to_cart_button.'
                </div>';
                }
            }else{
                echo '<p>Stay tuned for merch drop.</p>';
            } 
            ?>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 ShopEase. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
