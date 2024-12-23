<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Set content type to JSON

// connect to database
include("db_connection.php");


// recieve the data
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $productId = intval($_POST['product_id']);

    // fetch current quantity of the product
    $query = "SELECT quantity FROM products WHERE product_id = $productId";
    $result = mysqli_query($conn, $query);

    if($result && $row = mysqli_fetch_assoc($result)){
        $currentStock = $row['quantity'];

        if($currentStock>0){
            // decrement stock quantity by 1
            $newStock = $currentStock-1;
            // update new quantity in the database
            $newQuery = "UPDATE products SET quantity = $newStock WHERE product_id = $productId";
            mysqli_query($conn, $newQuery);

            // get the updated stock quantity to be able to refresh the page accordingly
            echo json_encode(["status" => "success", "new_stock" => $newStock]);
        } else {
            echo json_encode(["status" => "out_of_stock"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Product not found"]);
    }

    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
    }
?>