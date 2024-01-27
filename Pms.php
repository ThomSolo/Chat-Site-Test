<!--Notes-->
<!--This is a page that allows users to view their private messages. Even though the Chatroom
can basically do the same thing, this page will only show private messages that the user has been sent.-->

<?php //Pms.php
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

// Gets the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Selects private messages from the Message table
$selectQuery = "SELECT m.MessageID, m.Content, m.Timestamp, 
                u.Username AS Sender, u.UserType AS SenderType,
                mu.Username AS Recipient, mu.UserType AS RecipientType
                FROM Message m
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
    $selectStatement->execute();
    $privateMessages = $selectStatement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error executing query: " . $e->getMessage();
}

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

    <title>Private Messages</title>
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
        <h1>Private Messages</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <div class="private-messages">
            <?php
            if (!empty($privateMessages)) {
                foreach ($privateMessages as $privateMessage) {
                    echo "<div class='private-message'>";
                    echo "<strong>{$privateMessage['Sender']}</strong> ({$privateMessage['SenderType']}) to ";
                    echo "<strong>{$privateMessage['Recipient']}</strong> ({$privateMessage['RecipientType']}) - {$privateMessage['Timestamp']}<br>";
                    echo "{$privateMessage['Content']}";
                    echo "</div>";

                    // Line break
                    echo "<br>";

                }
            } else {
                echo "<p>No private messages available.</p>";
            }
            ?>
        </div>
    </main>



    <footer class="bg-dark text-white text-center py-2">
        &copy; CPS 4951 Final Proj. (Testing)
    </footer>

</body>

</html>