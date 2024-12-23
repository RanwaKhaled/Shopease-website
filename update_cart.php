<?php
include("db_connection.php");

// Get product name and action from POST request
$productName = $_POST['product_name'];
$action = $_POST['action'];
$current_quantity = intval($_POST['current_quantity']);

if ($action == 'increase') {
    // Decrease the product stock in the database
    $query = "UPDATE products SET quantity = quantity - 1 WHERE name = '$productName' AND quantity > 0";
    mysqli_query($conn, $query);
} elseif ($action == 'decrease') {
    // Increase the product stock in the database
    $query = "UPDATE products SET quantity = quantity + 1 WHERE name = '$productName'";
    mysqli_query($conn, $query);
} elseif ($action == 'remove') {
    // Restore the product stock in the database (based on cart item data)
    // Ideally, you should track the previous quantity or have a way to determine this
    // Example, this query assumes we know the original quantity change before removal
    $query = "UPDATE products SET quantity = quantity + '$current_quantity' WHERE name = '$productName'";
    mysqli_query($conn, $query);
}

echo json_encode(["status" => "success"]);
?>
