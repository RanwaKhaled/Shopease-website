<?php
// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// Initialize a variable for the status message (used for debugging only)
$statusMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();  
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'Hassanwaleed222@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'trjo abut texa tlts';   // Replace with your app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        // Recipients
        $mail->setFrom('Hassanwaleed222@gmail.com', 'ShopEase'); // Replace with your Gmail address
        $mail->addAddress('Hassanwaleed222@gmail.com', 'ShopEase Admin'); // Where the message will be sent

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Us Message';
        $mail->Body    = "
            <p>You have received a new message from your website:</p>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>$message</p>
        ";

        // Send the email
        $mail->send();

        // Redirect to Thank You Page
        header("Location: thankyou.php");
        exit();
    } catch (Exception $e) {
        $statusMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ShopEase</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="signup.php">Sign Up</a></li>
                <li><a href="signin.php">Sign In</a></li>
                <li><a href="cart.php">Cart (<span id="cart-count">0</span>)</a></li>
                <li><a href="account.php">Account</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="contact-us">
            <h2>Contact Us</h2>
            <?php if ($statusMessage): ?>
                <p class="status-message error"><?= $statusMessage; ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 ShopEase. All rights reserved.</p>
    </footer>
</body>
</html>
