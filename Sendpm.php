<!--Name: Solomon Thomas -->

<!--Class: CPS 3351 -->

<!--Group Project: Phase V (Phase 5)-->

<!--Notes-->
<!--This page allows the user to send a private message to the user in choice. Once they
have selected that user, they can send the message.-->

<?php //Sendpm.php
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
    // Redirects to the login page if not logged in
    header('Location: Login.php');
    exit();
}

// Get the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Handles sending private messages
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendPrivateMessage'])) {
    $messageContent = $_POST["privateMessageContent"];
    $recipientID = $_POST["recipientID"]; // Assuming you are getting the recipient's ID correctly

    // Inserts message into the Message table
    $insertQuery = "INSERT INTO Message (UserID, RecipientID, Content, IsPrivate) VALUES (:loggedInUserID, :recipientID, :content, 1)";
    $insertStatement = $pdo->prepare($insertQuery);

    // Binds parameters
    $insertStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
    $insertStatement->bindParam(':recipientID', $recipientID, PDO::PARAM_INT);
    $insertStatement->bindParam(':content', $messageContent, PDO::PARAM_STR);

    // Executes the query
    try {
        $insertStatement->execute();
        // Redirects to Pms.php after sending the private message
        header('Location: Pms.php');
        exit();
    } catch (PDOException $e) {
        echo "Error sending private message: " . $e->getMessage();
    }
}

// Retrieves the list of users for recipient selection
$userListQuery = "SELECT UserID, Username FROM UserAccount";
$userListStatement = $pdo->prepare($userListQuery);
$userListStatement->execute();
$userList = $userListStatement->fetchAll(PDO::FETCH_ASSOC);

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

    <title>Send Private Messages</title>
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
            // Displays the Logout link if the user is logged in
            if (isset($_SESSION['loggedInUserID'])) {
                echo '<a href="Logout.php">Logout</a>';
            }
            ?>
    </nav>

    <header class="bg-secondary text-white text-center py-2">
        <h1>Send Private Message</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <form method="post" action="">
            <label for="privateMessageContent">Type Message Here:</label>
            <textarea name="privateMessageContent" rows="3" required></textarea><br>

            <label for="recipientID">Who would you like to send this meesage to:</label>
            <select name="recipientID" required>
                <?php
                foreach ($userList as $user) {
                    echo "<option value='{$user['UserID']}'>{$user['Username']}</option>";
                }
                ?>
            </select><br>

            <input type="submit" name="sendPrivateMessage" value="Send Message">
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-2">
        &copy; CPS 4951 Final Proj. (Testing)
    </footer>

</body>

</html>