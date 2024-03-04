<?php 
//Pms.php

/*Notes
This is a page that allows users to view their private messages. Even though the Chatroom
can basically do the same thing, this page will only show private messages that the user has been sent.*/

include "dbcon.php";

// Starts the session
session_start();

// Checks if the user is logged in
if (!isset($_SESSION['loggedInUserID'])) {
    // Redirects to the login page if not logged in
    header('Location: Signin_up.php');
    exit();
}

// Gets the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Creates a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throws PDOException if connection fails
}

// Selects private messages from the Messages table
$selectQuery = "SELECT m.MessageID, m.Content, m.Timestamp, 
                u.Username AS Sender, u.UserType AS SenderType,
                mu.Username AS Recipient, mu.UserType AS RecipientType
                FROM Messages m
                LEFT JOIN UserAccount u ON m.UserID = u.UserID
                LEFT JOIN UserAccount mu ON m.RecipientID = mu.UserID
                WHERE m.IsPrivate = 1 AND (m.UserID = :loggedInUserID OR m.RecipientID = :loggedInUserID2)
                ORDER BY m.Timestamp DESC";
$selectStatement = $pdo->prepare($selectQuery);

// Binds parameters
$selectStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
$selectStatement->bindParam(':loggedInUserID2', $loggedInUserID, PDO::PARAM_INT);

// Initialize $privateMessages variable
$privateMessages = [];

// Executes the query
try {
    $selectStatement->execute(); // Execute the prepared statement
    $privateMessages = $selectStatement->fetchAll(PDO::FETCH_ASSOC); // Fetch all the private messages and store them in the $privateMessages variable
} catch (PDOException $e) {
    echo "Error executing query: " . $e->getMessage(); // If an exception (error) occurs during execution, catch it and display an error message
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

    <title>Private Messages</title>
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

        <h1>Private Messages</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
    <!-- flex-grow-1, d-flex, flex-column: These are Bootstrap classes for styling the main content area. -->

        <div class="private-messages">
            <!-- private-messages: From the custom css that styles the webpage-->

            <?php
    // Check if there are private messages available
    if (!empty($privateMessages)) {
        // Loop through each private message
        foreach ($privateMessages as $privateMessage) {
            // Display each private message within a div with the class 'private-message'
            echo "<div class='private-message'>";
            
            // Display the sender and recipient information along with the timestamp
            echo "<strong>{$privateMessage['Sender']}</strong> ({$privateMessage['SenderType']}) to ";
            echo "<strong>{$privateMessage['Recipient']}</strong> ({$privateMessage['RecipientType']}) - <span class='timestamp' data-timestamp='{$privateMessage['Timestamp']}'>{$privateMessage['Timestamp']}</span><br>";
            
            // Display the content of the private message
            echo "{$privateMessage['Content']}";
            
            // Close the div tag
            echo "</div>";

            // Output a line break after each private message
            echo "<br>";
        }
    } else {
        // If there are no private messages available, display a message
        echo "<p>No private messages available.</p>";
    }
?>

        </div>
    </main>



    <footer class="bg-dark text-white text-center py-2">
    <!-- bg-dark, text-white, text-center, py-2: These are Bootstrap classes for styling the footer. -->
    
        &copy; (Testing)
    </footer>

     <!-- JavaScript to convert UTC timestamps to local time -->
  <script>
 document.addEventListener("DOMContentLoaded", function () {
            const timestamps = document.querySelectorAll('.timestamp');
            timestamps.forEach(timestamp => {
                const date = new Date(timestamp.getAttribute('data-timestamp'));
                timestamp.textContent = date.toLocaleString();
            });
        });
    </script>
</body>

</html>