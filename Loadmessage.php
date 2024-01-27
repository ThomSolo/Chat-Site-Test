<!--Notes-->
<!--This file is in works with the jquery (from Chatroom.php) mentioned earlier.
It isn't really needed. All it does is just get the last 50 messages from the
"Messages" table in SQL and display them in Chatroom.php-->

<?php // Loadmessage.php

$host = 'localhost';    // Change as necessary
$data = 'testbase'; // Change as necessary
$user = 'tester';        // Change as necessary
$pass = 'comptest';        // Change as necessary
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts =
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
// Starts the session
session_start();

// Checks if the user is logged in
if (!isset($_SESSION['loggedInUserID'])) {
    // Redirect or handle the case where the user is not logged in
    exit("User not logged in");
}

// Gets the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


// Selects the latest 50 messages from the Message table
$selectQuery = "SELECT m.MessageID, m.Content, m.IsPrivate, m.Timestamp, u.UserID, u.Username, u.UserType 
                FROM Message m
                LEFT JOIN UserAccount u ON m.UserID = u.UserID
                WHERE m.IsPrivate = 0
                ORDER BY m.Timestamp DESC LIMIT 50";

$statement = $pdo->prepare($selectQuery);
$statement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
$statement->execute();
$messages = $statement->fetchAll(PDO::FETCH_ASSOC);

// Displays the messages
foreach ($messages as $message) {
    $messageClass = (isset($message['UserID']) && $message['UserID'] == $loggedInUserID) ? 'user-message' : 'other-message';

    echo "<div class='chat-message $messageClass'>";

    // Checks if 'UserID' index exists in the $message array
    if (isset($message['UserID'])) {
        echo "<strong>{$message['Username']}</strong> ({$message['UserType']}): ";
    }

    echo $message['Content'];

    if (isset($message['IsPrivate']) && $message['IsPrivate']) {
        echo " <em>(Private)</em>";
    }

    // Adds edit and delete buttons for the user's own messages
    if (isset($message['UserID']) && $message['UserID'] == $loggedInUserID) {
        echo " - {$message['Timestamp']} ";
        echo "<a href='Editmessage.php?messageID={$message['MessageID']}'>Edit</a> ";
        echo "<a href='Deletemessage.php?messageID={$message['MessageID']}'>Delete</a>";
    } else {
        echo " - {$message['Timestamp']}";
    }

    echo "</div>";

    // Line break
    echo "<br>";
}
?>