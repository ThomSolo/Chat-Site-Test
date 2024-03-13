<?php // Chatroom.php

/*Notes
This is a link to the other Chatrooms for Java, 
SQL, Python, and HTML/CSS*/

include "dbcon.php"; // Includes the database connection file

session_start(); // Start the session

// Checks if the user is logged in
if (!isset($_SESSION['loggedInUserID'])) {
    // Redirects to the login page if not logged in
    header('Location: Signin_up.php');
    exit();
}

// Gets the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];
$loggedInUsername = $_SESSION['loggedInUsername'];

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

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <title>Chat Room -
        <?php echo $loggedInUsername; ?> <!-- For displaying the user's username by the Title -->
    </title>

    <script src="jquery-3.7.1.min.js"></script>
    <!-- Linking the java script query in the files named jquery-3.7.1.min.js -->

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
        <h1>Chat Room -
            <?php echo $loggedInUsername; ?> <!-- Shows the User's username on the header of the page by "Chat Room" -->
        </h1>
    </header>

    <main>

        <h2>Please select the Chatroom you want to go to</h2>
        <ul>
            <li><a href="java_chat.php">Java Chat</a></li> <!-- link to the java_chat.php -->
            <li><a href="sql_chat.php">SQL Chat</a></li> <!-- link to the sql_chat.php -->
            <li><a href="python_chat.php">Python Chat</a></li> <!-- link to the python_chat.php -->
            <li><a href="htmlcss_chat.php">HTML/CSS Chat</a></li> <!-- link to the htmlcss.php -->
    </main>

    <footer>

        &copy; (Testing)
    </footer>

</body>

</html>