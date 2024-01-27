<!--Notes-->
<!--This file has a big role. This takes the user to a page where they can create an account.
It uses session variables, regular expressions, and jquery (with ajax) in the process
of creating an account.-->

<?php // Create.php
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

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Handles form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieves form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validates username
    $usernamePattern = "/^[a-zA-Z0-9_-]{3,20}$/";
    if (!preg_match($usernamePattern, $username)) {
    } else {

        // Validates password
        $passwordPattern = "/^(?=.*[a-zA-Z\d!@#$%^&*()_+]).{10,}$/";
        if (!preg_match($passwordPattern, $password)) {
        } else {

            // Inserts data into the UserAccount table
            $query = "INSERT INTO UserAccount (Username, Password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($query);

            // Binds parameters
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);

            // Executes the query and handle any errors
            try {
                $stmt->execute();

                // Sets a cookie for the newly created user
                setcookie('newUser', $username, time() + (86400 * 30), "/"); // 86400 seconds = 1 day

                $resultMessage = "<p class='success'>Account created successfully!</p>";
            } catch (PDOException $e) {
                // Handle database error
                if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    // Duplicate entry error
                    $resultMessage = "<p class='error'>Username is already taken. Please choose a different username.</p>";
                } else {
                    // Other database error
                    $resultMessage = "<p class='error'>Error creating account: " . $e->getMessage() . "</p>";
                }
            }
        }
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

    <title>Create Account</title>

    <script src="jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function () {
            // Functions to check username availability asynchronously
            function checkUsernameAvailability() {
                var username = $("#username").val(); // Gets the entered username

                $.ajax({
                    type: "POST",
                    url: "Checkuseravail.php", // A separate PHP file for handling username availability
                    data: { username: username },
                    success: function (response) {
                        // Updates the availability status
                        $("#usernameAvailability").html(response);
                    }
                });
            }

            // Binds the function to the username input change event
            $("#username").on("input", function () {
                checkUsernameAvailability();
            });
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
            // Displays the Logout link if the user is logged in
            if (isset($_SESSION['loggedInUserID'])) {
                echo '<a href="Logout.php">Logout</a>';
            }
            ?>
    </nav>

    <header class="bg-secondary text-white text-center py-2">
        <h1>Create Account</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <div class="center-box">
            <p>Fill in information below</p><br>

            <?php
            // Handles form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo $resultMessage;
                // Retrieves form data
                $username = $_POST["username"];
                $password = $_POST["password"];

            }
            ?>

            <?php
            // Move username validation within the form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST["username"];
                $usernamePattern = "/^[a-zA-Z0-9_-]{3,20}$/";
                if (!preg_match($usernamePattern, $username)) {
                    echo "<p class='error'>Invalid username. It must be 3 to 20 characters and can only contain letters, numbers, etc.</p>";
                }
            }
            ?>

            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" name="username" required><br><br>


                <?php
                // Move password validation within the form
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $password = $_POST["password"];
                    $passwordPattern = "/^(?=.*[a-zA-Z\d!@#$%^&*()_+]).{10,}$/";
                    if (!preg_match($passwordPattern, $password)) {
                        echo "<p class='error'>Invalid password. at least one uppercase letter, one lowercase letter, one number, and one special character.</p>";
                    }
                }
                ?>

                <label for="password">Password:</label>
                <input type="password" name="password" required><br><br>



                <input type="submit" value="Create Account">
            </form>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-2">
        &copy; CPS 4951 Final Proj. (Testing)
    </footer>

</body>

</html>