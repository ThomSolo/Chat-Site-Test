<?php
//htmlcss_edit_mes.php

/*Notes
For HTML/CSS Chat
This allows the user to edit their own message.
*/

include "dbcon.php"; // Include the database connection file

session_start(); // Start the session

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedInUserID'])) {
    header('Location: Signin_up.php');
    exit();
}

try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Create a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throw PDOException if connection fails
}

$loggedInUserID = $_SESSION['loggedInUserID']; // Get the logged-in user's ID

// Redirect to chatroom if messageID is not provided or not a valid integer
if (!isset($_GET['messageID']) || !filter_var($_GET['messageID'], FILTER_VALIDATE_INT)) {
    header('Location: Chatroom.php');
    exit();
}

$messageID = $_GET['messageID']; // Get the messageID from the URL

// Prepare and execute query to select message from database
$selectQuery = "SELECT * FROM HTMLCSSChat WHERE MessageID = :messageID AND UserID = :loggedInUserID";
$selectStatement = $pdo->prepare($selectQuery);
$selectStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
$selectStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

try {
    $selectStatement->execute();
    $message = $selectStatement->fetch(PDO::FETCH_ASSOC); // Fetch the message data
} catch (PDOException $e) {
    echo "Error retrieving message: " . $e->getMessage(); // Display error message if retrieval fails
    exit();
}

// Redirect to chatroom if message does not exist or user is not authorized to edit it
if (!$message) {
    header('Location: Chatroom.php');
    exit();
}

// Update message if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateMessage'])) {
    $editedContent = $_POST["editedContent"]; // Get the edited content from the form

    // Prepare and execute query to update message in database
    $updateQuery = "UPDATE HTMLCSSChat SET Content = :editedContent WHERE MessageID = :messageID AND UserID = :loggedInUserID";
    $updateStatement = $pdo->prepare($updateQuery);
    $updateStatement->bindParam(':editedContent', $editedContent, PDO::PARAM_STR);
    $updateStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
    $updateStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

    try {
        $updateStatement->execute(); // Execute the update query
    } catch (PDOException $e) {
        echo "Error updating message: " . $e->getMessage(); // Display error message if update fails
    }

    header('Location: htmlcss_chat.php'); // Redirect back to the chatroom after updating
    exit();
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

    <title>Edit Message</title> 
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

        <h1>Edit Message</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
    <!-- flex-grow-1, d-flex, flex-column: These are Bootstrap classes for styling the main content area. -->

    <div class="chat-box">
        <!-- chat-box: From the custom css that styles the webpage-->

        </div>

        <div class="message-input">
        <!-- message-input: From the custom css that makes a division and styles the webpage -->

            <form method="post" action="">
                <label for="editedContent">Edit Message:</label>
                <textarea name="editedContent" rows="3" required><?php echo $message['Content']; ?></textarea><br> <!-- Text area for entering the message content -->

                <input type="submit" name="updateMessage" value="Update Message"> <!-- Submit button for updating the message -->
            </form>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-2">
    <!-- bg-dark, text-white, text-center, py-2: These are Bootstrap classes for styling the footer. -->

        &copy; (Testing)
    </footer>

</body>

</html>
