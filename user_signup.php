<?php
    include("db_connection.php");
    if(isset($_POST['signup'])){  // when the submit button is pressed
        // get the values from the form
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check if the user already exists
        $sql = "SELECT * From users where username = '$username'";
        // store the result 
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        // store the nbr of rows returned (nbr of matches which should be one)
        $count = mysqli_num_rows($result);
        if($count==1){
            // redirect to products page
            header("Location: signup.php?error=1");
        }
        // we add the new user info to the database
        else{
            $insertQuery = "insert into users(email, username, password) values ('$email','$username','$password')";
            if($conn->query($insertQuery)==TRUE){
                // if registration successful redirect the user to the log in page
                header("Location: signin.php");
            }
            else{
                echo "Error:".$conn->error;
            }
        }
    }
?>