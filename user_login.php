<?php
session_start();
// include the database connection file
include("db_connection.php");
if(isset($_POST['submit'])){
    // extract the email and password from the form in the signin.php
    $email = $_POST['email'];  // using the value of the name attribute
    $password = $_POST['password'];

    // create the query: check if the query is present in the timezone_abbreviations_list
    $sql = "select * from users where email = '$email' and password = '$password'";
    // store the result 
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    // store the nbr of rows returned (nbr of matches which should be one)
    $count = mysqli_num_rows($result);
    if($count==1){
        // set login flag to true
        $_SESSION['user_logged_in'] = true;

        // redirect to products page
        header("Location: products.php");
        $_SESSION['username'] = $row['username']; // Store username in session
        $_SESSION['email'] = $row['email'];       // Store email in session

        // Redirect to products page
        header("Location: products.php");
        exit();
    }
    else{
        // write a message to say the password is incorrect
        $_SESSION['user_logged_in'] = false;
        header("Location: signin.php?error=1");
        exit;
    }
}

?>