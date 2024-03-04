<?php 
// Loggednotice.php

/*Notes
This page allows informs the user (if logged in) that they are already logged in.
It's triggered if the logged in user clicks on "Login.php".*/

include "dbcon.php"; // Includes the database connection file


session_start(); // Starts a session



// Creates connection
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Including Bootstrap CSS from CDN -->

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) (From a CDN) -->
    <!-- A content delivery network (CDN) is a network of interconnected servers that speeds up webpage loading for data-heavy applications. -->
    <!-- Source: https://aws.amazon.com/what-is/cdn/#:~:text=A%20content%20delivery%20network%20(CDN,loading%20for%20data%2Dheavy%20applications.) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
    function showPrompt() {
        <?php
        // Checks if the user is logged in
        if (!isset($_SESSION['loggedInUserID'])) {

            // If not logged in, show the prompt
            echo 'alert("Please create an account or login to enter the Chat Room");';
        }
        ?>
    }
</script>

    <title>Logged in Notice</title>
</head>

<body class="d-flex flex-column h-100">
    <!-- d-flex, flex-column, h-100: Bootstrap classes to make the body of html flex container with column layout. 
    Also ensuring the body uses full hgiht of viewpoert ('h-100') alloing it to be flexible and responsive. -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- navbar, navbar-expand-lg, navbar-dark, bg-dark: These are Bootstrap classes for styling the navigation bar. -->

        <div class="container">
            <!-- container: Bootstrap class for creating a fixed-width container to hold and center the content within it. -->
            
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
        </div>
    </nav>

    <header class="bg-secondary text-white text-center py-2">
        <!-- bg-secondary, text-white, text-center, py-2: These are Bootstrap classes for styling the header. -->

        <h1>You Are Already Logged In!</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <!-- flex-grow-1, d-flex, flex-column: These are Bootstrap classes for styling the main content area. -->

        <p>You are already logged in!</br></br>
            Please feel free to log out, or choose another option from the tabs above.
        <p>
    </main>

    <footer class="bg-dark text-white text-center py-2">
        <!-- bg-dark, text-white, text-center, py-2: These are Bootstrap classes for styling the footer. -->

        &copy; (Testing)
    </footer>

</body>

</html>