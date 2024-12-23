<?php
include("db_connection.php");

$productName = $_GET['product_name'];

$query = "SELECT quantity FROM products WHERE name = '$productName'";
$result = mysqli_query($conn, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $stock = $row['quantity'];
    echo json_encode(["status" => "success", "stock" => $stock]);
} else {
    echo json_encode(["status" => "error", "message" => "Product not found"]);
}
?>
