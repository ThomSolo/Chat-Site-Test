<?php
//htmlcss_chat.php

/*Notes
This is a public chat room for (HTML/CSS).
The user can comment, edit, and/or delete their own messages.
*/

include "dbcon.php"; // Includes the database connection file

session_start(); // Start the session

if (!isset($_SESSION['loggedInUserID'])) { // Checks if the user is logged in
    header('Location: Signin_up.php'); // Redirects to the login page if not logged in
    exit();
}

$loggedInUserID = $_SESSION['loggedInUserID']; // Gets the logged-in user's ID
$loggedInUsername = $_SESSION['loggedInUsername']; // Gets the logged-in user's username

try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Creates a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throws PDOException if connection fails
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendMessage'])) { // Checks if the form is submitted and the send message button is clicked
    
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_token']) { // Verify token
        header('Location: htmlcss_chat.php');
        exit();
    }

    $messageContent = $_POST["messageContent"]; // Retrieves the message content from the form
    $isPrivate = isset($_POST["isPrivate"]) ? 1 : 0; // Checks if the message is private or not

    // Inserts the message into the database
    $insertQuery = "INSERT INTO HTMLCSSChat (UserID, Content, IsPrivate) VALUES (:userID, :content, :isPrivate)";
    $insertStatement = $pdo->prepare($insertQuery);

    // Binds parameters
    $insertStatement->bindParam(':userID', $loggedInUserID, PDO::PARAM_INT);
    $insertStatement->bindParam(':content', $messageContent, PDO::PARAM_STR);
    $insertStatement->bindParam(':isPrivate', $isPrivate, PDO::PARAM_BOOL);

    try {
        $insertStatement->execute(); // Executes the insert query
        unset($_SESSION['csrf_token']); // Remove token to prevent resubmission
    } catch (PDOException $e) {
        echo "Error sending message: " . $e->getMessage(); // Displays an error message if insertion fails
    }
}

// Generate CSRF token
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

// Selects the latest 50 messages from the database
$selectQuery = "SELECT m.MessageID, m.Content, m.IsPrivate, m.Timestamp, u.Username, m.UserID
                FROM HTMLCSSChat m
                LEFT JOIN UserAccount u ON m.UserID = u.UserID
                ORDER BY m.Timestamp DESC LIMIT 50";

$statement = $pdo->prepare($selectQuery); // Prepares the select query
$statement->execute(); // Executes the select query
$messages = $statement->fetchAll(PDO::FETCH_ASSOC); // Fetches the messages from the database


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

    <title>HTML/CSS Chat Room - <?php echo $loggedInUsername; ?></title> <!-- For displaying the user's username by the Title --> 
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

    <h1>HTML/CSS Chat Room - <?php echo $loggedInUsername; ?></h1> <!-- Shows the User's username on the header of the page by "Chat Room" -->
</header>

<main class="flex-grow-1 d-flex flex-column">
    <!-- flex-grow-1, d-flex, flex-column: These are Bootstrap classes for styling the main content area. -->

    <div class="chat-box">
        <!-- chat-box: From the custom css that styles the webpage-->

        <?php
        foreach ($messages as $message) { // Iterates through each message
            $messageClass = ''; // Initializes the message class

            if (isset($message['UserID'])) { // Checks if the message has a user ID
                // Sets the message class based on whether it's from the logged-in user or another user
                $messageClass = ($message['UserID'] == $loggedInUserID) ? 'user-message' : 'other-message';
            }

            // Displays the message content, username, and timestamp
            echo "<div class='chat-message $messageClass' data-timestamp='{$message['Timestamp']}'>";
                echo "<strong>{$message['Username']}</strong>: {$message['Content']}";

            if (isset($message['UserID']) && $message['UserID'] == $loggedInUserID) { // Checks if the message is from the logged-in user

                // Displays edit and delete links for the logged-in user's messages
                echo " <a href='htmlcss_edit_mes.php?messageID={$message['MessageID']}'>Edit</a>"; // Links to "htmlcss_edit_mes.php", it edits messages.
                echo " <a href='htmlcss_delete_mes.php?messageID={$message['MessageID']}'>Delete</a>"; // Links to "htmlcss_edit_mes.php", it deletes messages.
            }

            echo "</div>"; // Closes the chat message div
        }
        ?>
    </div>

    <div class="message-input">
        <!-- message-input: From the custom css that makes a division and styles the webpage -->

        <form method="post" action="">
        <input type="hidden" name="token" value="<?php echo $csrf_token; ?>">  <!-- For amking a message unique (preventing errors) -->
            <textarea name="messageContent" rows="3" required placeholder="Type your message..."></textarea> <!-- Text area for entering the message content -->
            <input type="submit" name="sendMessage" value="Send"> <!-- Submit button for sending the message -->
        </form>
    </div>
</main>

<footer class="bg-dark text-white text-center py-2">
    <!-- bg-dark, text-white, text-center, py-2: These are Bootstrap classes for styling the footer. -->

    &copy; (Testing)
</footer>

  <!-- JavaScript to convert UTC timestamps to local time -->
  <script>
        document.addEventListener("DOMContentLoaded", function () {
            const chatMessages = document.querySelectorAll('.chat-message');
            chatMessages.forEach(message => {
                const timestamp = message.getAttribute('data-timestamp');
                const date = new Date(timestamp);
                message.innerHTML += ' - ' + date.toLocaleString();
            });
        });
    </script>

</body>

</html>
