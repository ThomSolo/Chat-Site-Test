<!--Notes-->
<!--This file a decent role. This is the login page where the user can login in with the 
credentials that were created on the "Create.php" page. It uses session varibales.-->

<?php // Login.php
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

if (isset($_SESSION['loggedInUserID'])) {
    header('Location: Loggednotice.php'); // Change 'Welcome.php' to the page you want to redirect to
    exit();
}

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Handles login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $loginUsername = $_POST["loginUsername"];
    $loginPassword = $_POST["loginPassword"];

    // Checks the user's credentials
    $query = "SELECT * FROM UserAccount WHERE Username = :username AND Password = :password";
    $stmt = $pdo->prepare($query);

    // Binds parameters
    $stmt->bindParam(':username', $loginUsername, PDO::PARAM_STR);
    $stmt->bindParam(':password', $loginPassword, PDO::PARAM_STR);

    // Executes the query and handle any errors
    try {
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            // Set session variables for logged-in user
            $_SESSION['loggedInUserID'] = $result['UserID'];
            $_SESSION['loggedInUsername'] = $result['Username'];
            header('Location: Chatroom.php');
            exit();
        } else {
            $loginMessage = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $loginMessage = "Error during login: " . $e->getMessage();
    }
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

    <script>
        function showPrompt() {
            <?php
            // Checks if the user is logged in
            if (!isset($_SESSION['loggedInUserID'])) {
                // If not logged in, show the prompt
                echo 'alert("Please create an account or login to enter the Chat Room");';
            }
            ?>
        }
    </script>
    <title>Login Page</title>
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
        <h1>Login</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <div class="center-box">
            <p>Fill in information below</p>

            <?php
            // Handles login form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieves form data
                $loginUsername = $_POST["loginUsername"];
                $loginPassword = $_POST["loginPassword"];
            }
            ?>
            <?php
            // Displays login message
            if (!empty($loginMessage)) {
                echo "<p>$loginMessage</p>";
            }
            ?>

            <form method="post" action="">
                <label for="loginUsername">Username:</label>
                <input type="text" name="loginUsername" required><br>

                <label for="loginPassword">Password:</label>
                <input type="password" name="loginPassword" required><br>

                <input type="submit" value="Login">
            </form>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-2">
        &copy; CPS 4951 Final Proj. (Testing)
    </footer>

</body>

</html>