<!--Notes-->
<!--This is the rules page. It has a simple job. All it does is
display a list of rules that users must follow or else actions will take place.-->

<?php // Rules.php
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

// Creates connection
session_start();

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
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

    <title>Rules Page</title>
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
        <h1>Rules</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <p>
            Rules: </br></br>

            1. Please be respectful to others.</br>
            2. No profanity or non-work-friendly language is not allowed</br>
            3. Do not create inappropriate usernames or chat messages</br></br>

            Please abide by these rules to avoid your account being banned.</br></br>

            Besides that, please enjoy your time here.
        </p>
    </main>


    <footer class="bg-dark text-white text-center py-2">
        &copy; CPS 4951 Final Proj. (Testing)
    </footer>

</body>

</html>