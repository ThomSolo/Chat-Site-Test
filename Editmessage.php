<?php
// Editmessage.php

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

    // Retrieves the existing message content
    $selectQuery = "SELECT * FROM Message WHERE MessageID = :messageID AND UserID = :loggedInUserID";
    $selectStatement = $pdo->prepare($selectQuery);

    // Binds parameters
    $selectStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
    $selectStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

    // Executes the query
    try {
        $selectStatement->execute();
        $message = $selectStatement->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error retrieving message: " . $e->getMessage();
        exit();
    }

    // Displays the message editing form
    if ($message) {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

            <!-- Your custom stylesheet -->
            <link rel="stylesheet" href="styles.css">

            <!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

            <title>Edit Message</title>
        </head>

        <body class="d-flex flex-column h-100">

            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <a href="Homepage.php">Home</a>
                    <a href="Rules.php">Rules</a>
                    <a href="Create.php">Create Account</a>
                    <a href="Login.php">Login</a>
                    <a href="Chatroom.php">Chat Room</a>
                    <a href="Sendpm.php">Send Private Message</a>
                    <a href="Pms.php">Private Messages</a>
                    <?php
                    // Display the Logout link if the user is logged in
                    if (isset($_SESSION['loggedInUserID'])) {
                        echo '<a href="Logout.php">Logout</a>';
                    }
                    ?>
            </nav>

            <header class="bg-secondary text-white text-center py-2">
                <h1>Edit Message</h1>
            </header>

            <main class="flex-grow-1 d-flex flex-column">
                <div class="chat-box">

                </div>

                <div class="message-input">
                    <form method="post" action="">
                        <label for="editedContent">Edit Message:</label>
                        <textarea name="editedContent" rows="3" required><?php echo $message['Content']; ?></textarea><br>

                        <input type="submit" name="updateMessage" value="Update Message">
                    </form>
                </div>
            </main>

            <footer class="bg-dark text-white text-center py-2">
                &copy; CPS 4951 Final Proj. (Testing)
            </footer>

        </body>

        </html>

        <?php
    } else {
        // Redirects back to the chatroom if the message doesn't exist or doesn't belong to the user
        header('Location: Chatroom.php');
        exit();
    }
} else {
    // Redirects back to the chatroom if messageID is not set or invalid
    header('Location: Chatroom.php');
    exit();
}
// Checks if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateMessage'])) {
    $editedContent = $_POST["editedContent"];

    // Updates the message content in the Message table
    $updateQuery = "UPDATE Message SET Content = :editedContent WHERE MessageID = :messageID AND UserID = :loggedInUserID";
    $updateStatement = $pdo->prepare($updateQuery);

    // Binds parameters
    $updateStatement->bindParam(':editedContent', $editedContent, PDO::PARAM_STR);
    $updateStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
    $updateStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

    // Executes the query
    try {
        $updateStatement->execute();
    } catch (PDOException $e) {
        echo "Error updating message: " . $e->getMessage();
    }

    // Redirects back to the chatroom after updating the message
    header('Location: Chatroom.php');
    exit();
}
?>