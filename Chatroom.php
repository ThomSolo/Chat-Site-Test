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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Including Bootstrap CSS from CDN -->

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) (From a CDN) -->
    <!-- A content delivery network (CDN) is a network of interconnected servers that speeds up webpage loading for data-heavy applications. -->
    <!-- Source: https://aws.amazon.com/what-is/cdn/#:~:text=A%20content%20delivery%20network%20(CDN,loading%20for%20data%2Dheavy%20applications.) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <title>Chat Room -
        <?php echo $loggedInUsername; ?> <!-- For displaying the user's username by the Title --> 
    </title>

    <script src="jquery-3.7.1.min.js"></script> <!-- Linking the java script query in the files named jquery-3.7.1.min.js -->

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

        <h1>Chat Room -
            <?php echo $loggedInUsername; ?> <!-- Shows the User's username on the header of the page by "Chat Room" -->
        </h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <!-- flex-grow-1, d-flex, flex-column: These are Bootstrap classes for styling the main content area. -->

        <div class="container mt-5">
            <!-- container, mt-5: These are Bootstrap classes for styling a responsive fixed-width container, is centered horizontally on the page, 
            and the "mt-5" dds margin to the top of the container, providing space between the header and the container. -->

            <h2 class="text-center">Please select the Chatroom you want to go to</h2>

            <div class="row justify-content-center mt-3">
                <!-- row, justify-content-center, mt-3: These are Bootstrap classes for styling a created row within the container to organize content, 
                this row is horizontally centered by "justify-content-center", and the "mt-3" class adds margin to the top of the row, 
                providing space between the previous element and the row. -->

                <div class="col-md-6">
                    <!-- col-md-6: A bootstrap class creating a grid system and used for creating a responsive layout. -->

                    <ul class="list-group">
                        <!-- list-group: A bootstrap compenent used to style unordered lists (<ul>) as list groups. -->


                        <!-- list-group-item: A bootstrap compenent used to style individual items within a list group (<ul> with the class "list-group"). -->
                        <li class="list-group-item"><a href="java_chat.php">Java Chat</a></li> <!-- link to the java_chat.php -->
                        <li class="list-group-item"><a href="sql_chat.php">SQL Chat</a></li> <!-- link to the sql_chat.php -->
                        <li class="list-group-item"><a href="python_chat.php">Python Chat</a></li> <!-- link to the python_chat.php -->
                        <li class="list-group-item"><a href="htmlcss_chat.php">HTML/CSS Chat</a></li> <!-- link to the htmlcss.php -->
                    </ul>
                </div>
            </div>
        </div>
    </main>



    <footer class="bg-dark text-white text-center py-2">
        <!-- bg-dark, text-white, text-center, py-2: These are Bootstrap classes for styling the footer. -->

        &copy; (Testing)
    </footer>

</body>

</html>