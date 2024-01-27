<!--Notes-->
<!--This file has a part in the "Chatroom.php". It has the function of deleting
messages that belong to the user.-->

<?php // Deletemessage.php

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
    // Redirect to the login page if not logged in
    header('Location: Login.php');
    exit();
}

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Gets the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];

// Ensures the messageID is set and is a valid integer
if (isset($_GET['messageID']) && filter_var($_GET['messageID'], FILTER_VALIDATE_INT)) {
    $messageID = $_GET['messageID'];

    // Deletes the message from the Message table
    $deleteQuery = "DELETE FROM Message WHERE MessageID = :messageID AND UserID = :loggedInUserID";
    $deleteStatement = $pdo->prepare($deleteQuery);

    // Binds parameters
    $deleteStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
    $deleteStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

    // Executes the query
    try {
        $deleteStatement->execute();
    } catch (PDOException $e) {
        echo "Error deleting message: " . $e->getMessage();
    }

    // Redirects back to the chatroom
    header('Location: Chatroom.php');
    exit();
} else {
    // Redirects back to the chatroom if messageID is not set or invalid
    header('Location: Chatroom.php');
    exit();
}
?>