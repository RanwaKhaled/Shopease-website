<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // If not logged in, redirect to the login page
    header("Location: signin.php");
    exit;
}

// Include the database connection
include("db_connection.php");

// Initialize variables
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User not found';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'User not found';


// Logout logic 
if (isset($_POST['logout'])){
    $_SESSION['user_logged_in'] = false;  // set the flag to false
    session_destroy();  // destroy session
    header("Location: index.php");  // redirect to home page
    exit();
}

// Update account logic
if (isset($_POST['update_account'])) {
    $new_email = $_POST['new_email'];
    $new_password = $_POST['new_password'];

    // Prepare the SQL query to update user details
    $sql = "UPDATE users SET email = ?, password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $new_email, $new_password, $username);
    $stmt->execute();

    // Update session variables with new data
    $_SESSION['email'] = $new_email;
    $_SESSION['password'] = $new_password;

    // Redirect to the same page to reflect changes
    header("Location: account.php");
    exit();
}

// Delete account logic
if (isset($_POST['delete_account'])) {
    $username = $_SESSION['username'];

    // Prepare SQL to delete the user account
    $sql = "DELETE FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Destroy the session and redirect to the home page
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="account.css">
</head>
<body>
    <div class="container">
        <h1>Account Details</h1>

        <!-- Display User Info -->
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>

        <!-- Update Account Form -->
        <h2>Update Your Account</h2>
        <form method="POST" class="account-data">
            <label for="new_email">New Email:</label>
            <input type="email" name="new_email" id="new_email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <button type="submit" name="update_account" style="background-color: green; color: white;">Update Account</button>
        </form>

        <!-- Delete Account Form -->
        <h2>Delete Your Account</h2>
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
            <button type="submit" name="delete_account" style="background-color: red; color: white;">Delete Account</button>
        </form>

        <!--Logout Button-->
        <form method="POST" style="background: none; box-shadow:none;">
            <button type="submit" name="logout" style="background-color: orange; color: white;">Log Out</button>
        </form>

        <a href="index.php" class="return-home">Return to Home</a>
    </div>

    
    <footer>
        <p>&copy; 2024 ShopEase. All rights reserved.</p>
    </footer>
</body>
</html>
