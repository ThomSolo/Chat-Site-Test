<?php // index.php

/*Notes
This is the homepage, but as an index.php 
Without it, errors would occur.
*/

include "dbcon.php";

// Creates connection
session_start();

try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Creates a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throws PDOException if connection fails
}
?>



<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <title>Home Page</title>
</head>


<body>
    <nav>

        <a href="Homepage.php">Home</a> <!-- Link to Homepage.php -->
        <a href="About_Us.php">About Us</a> <!-- Link to About_Us.php -->
        <a href="Signin_up.php">SignUp/Login</a> <!-- Link to Signin_up.php -->

        <?php
        // Displays the Logout link if the user is logged in
        if (isset($_SESSION['loggedInUserID'])) { // Checks if a user is logged in
            echo '<a href="Chatroom.php">Chat Room</a>'; // Link to Chatroom.php
            echo '<a href="Sendpm.php">Send Private Message</a>'; // Link to Sendpm.php
            echo '<a href="Pms.php">Private Messages</a>'; // Link to Pms.php
            echo '<a href="Logout.php">Logout</a>'; // Link to Logout.php
        }
        ?>
    </nav>

    <header>
        <h1>Home Page</h1>
    </header>

    <main>

        <p>Welcome to this Public Chat Room. </br>
            You must have an account to Log in and Talk to others. </br>
            Please, feel free to create an account. </br></br>
            Before you do anything, please view the "Rules" by clicking it above.
        </p>

    </main>

    <footer>
        &copy; (Testing)
    </footer>

</body>

</html>