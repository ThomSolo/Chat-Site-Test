<?php // About Us.php

/*Notes
This is an about us page. */

include "dbcon.php"; // Includes the database connection file


session_start(); // Starts a session


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

    <title>About Us</title>
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
        <h1>About Us</h1>
    </header>

    <main>
        <!-- flex-grow-1, d-flex, flex-column: These are Bootstrap classes for styling the main content area. -->

        <p> Information about group here </p>
        <!--
        <p>
            Rules: </br></br>

            1. Please be respectful to others.</br>
            2. No profanity or non-work-friendly language is not allowed</br>
            3. Do not create inappropriate usernames or chat messages</br></br>

            Please abide by these rules to avoid your account being banned.</br></br>

            Besides that, please enjoy your time here.
        </p>
        -->
    </main>


    <footer>

        &copy; (Testing)
    </footer>

</body>

</html>
