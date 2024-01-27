<!--Notes-->
<!--This file has a large role. This page is what this project focuses on.
On this page, users (and admin) can talk with eachother. The users can edit and
delete their comments. This uses Jqery with Ajax.-->

<?php // Chatroom.php
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

// Start sthe session
session_start();

// Checks if the user is logged in
if (!isset($_SESSION['loggedInUserID'])) {
    // Redirect to the login page if not logged in
    header('Location: Login.php');
    exit();
}

// Gets the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];
$loggedInUsername = $_SESSION['loggedInUsername'];

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Handles sending messages
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendMessage'])) {
    $messageContent = $_POST["messageContent"];
    $isPrivate = isset($_POST["isPrivate"]) ? 1 : 0; // Converts checkbox value to boolean

    // Inserts message into the Message table
    $insertQuery = "INSERT INTO Message (UserID, Content, IsPrivate) VALUES (:userID, :content, :isPrivate)";
    $insertStatement = $pdo->prepare($insertQuery);

    // Binds parameters
    $insertStatement->bindParam(':userID', $loggedInUserID, PDO::PARAM_INT);
    $insertStatement->bindParam(':content', $messageContent, PDO::PARAM_STR);
    $insertStatement->bindParam(':isPrivate', $isPrivate, PDO::PARAM_BOOL);

    // Executes the query
    try {
        $insertStatement->execute();
    } catch (PDOException $e) {
        echo "Error sending message: " . $e->getMessage();
    }
}

$selectQuery = "SELECT m.MessageID, m.Content, m.IsPrivate, m.Timestamp, u.UserID, u.Username, u.UserType 
                FROM Message m
                LEFT JOIN UserAccount u ON m.UserID = u.UserID
                WHERE m.IsPrivate = 0
                ORDER BY m.Timestamp DESC LIMIT 50";


$statement = $pdo->prepare($selectQuery);
$statement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
$statement->execute();
$messages = $statement->fetchAll(PDO::FETCH_ASSOC);



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

    <title>Chat Room -
        <?php echo $loggedInUsername; ?>
    </title>

    <script src="jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function () {
            // Function to load messages asynchronously
            function loadMessages() {
                $.ajax({
                    type: "GET",
                    url: "Loadmessage.php", // Use the new PHP file for loading messages
                    success: function (response) {
                        // Update the chat box with new messages
                        $(".chat-box").html(response);
                    }
                });
            }

            // Load messages on page load
            loadMessages();

            // Set an interval to periodically update messages (e.g., every 5 seconds)
            setInterval(loadMessages, 5000); // Adjust the interval as needed
        });
    </script>


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
        <h1>Chat Room -
            <?php echo $loggedInUsername; ?>
        </h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <div class="chat-box">
            <?php
            foreach ($messages as $message) {
                // Skip private messages in the chat room
                if (isset($message['IsPrivate']) && $message['IsPrivate']) {
                    continue;
                }

                $messageClass = (isset($message['UserID']) && $message['UserID'] == $loggedInUserID) ? 'user-message' : 'other-message';

                echo "<div class='chat-message $messageClass'>";

                // Check if 'UserID' index exists in the $message array
                if (isset($message['UserID'])) {
                    echo "<strong>{$message['Username']}</strong> ({$message['UserType']}): ";
                }

                echo $message['Content'];

                // Add edit and delete buttons for the user's own messages
                if (isset($message['UserID']) && $message['UserID'] == $loggedInUserID) {
                    echo " - {$message['Timestamp']} ";
                    echo "<a href='Editmessage.php?messageID={$message['MessageID']}'>Edit</a> ";
                    echo "<a href='Deletemessage.php?messageID={$message['MessageID']}'>Delete</a>";
                } else {
                    echo " - {$message['Timestamp']}";
                }

                echo "</div>";

                // Add a line break
                echo "<br>";
            }
            ?>
        </div>

        <div class="message-input">
            <form method="post" action="">
                <textarea name="messageContent" rows="3" required placeholder="Type your message..."></textarea>
                <input type="submit" name="sendMessage" value="Send">
            </form>
        </div>
    </main>



    <footer class="bg-dark text-white text-center py-2">
        &copy; CPS 4951 Final Proj. (Testing)
    </footer>

</body>

</html>