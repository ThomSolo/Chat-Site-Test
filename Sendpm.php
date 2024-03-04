<?php
//Sendpm.php

/*Notes
This page allows the user to send a private message to the user of choice. Once they
have selected that user, they can send the message.*/

// Including the database connection file
include "dbcon.php";

// Starting the session
session_start();

// Redirecting to the login page if the user is not logged in
if (!isset($_SESSION['loggedInUserID'])) {
    header('Location: Signin_up.php');
    exit();
}

// Getting the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];

// Creating a PDO database connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Handling sending private messages
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendPrivateMessage'])) {
    // Retrieving message content from the form
    $messageContent = $_POST["privateMessageContent"];

    // Retrieving recipient ID from the form
    if (isset($_POST["recipientID"])) {
        $recipientID = $_POST["recipientID"];
    } else {
        $createMessage = "<p class='error'> Error: Recipient user not specified.</p>";
    }

    // Checking if the recipient user exists
    $checkRecipientQuery = "SELECT COUNT(*) AS count FROM UserAccount WHERE UserID = :recipientID";
    $checkRecipientStatement = $pdo->prepare($checkRecipientQuery);
    $checkRecipientStatement->bindParam(':recipientID', $recipientID, PDO::PARAM_INT);
    $checkRecipientStatement->execute();
    $recipientExists = $checkRecipientStatement->fetchColumn();

    if ($recipientExists) {
        // Inserting message into the Messages table
        $insertQuery = "INSERT INTO Messages (UserID, RecipientID, Content, IsPrivate) VALUES (:loggedInUserID, :recipientID, :content, 1)";
        $insertStatement = $pdo->prepare($insertQuery);
        // Binding parameters
        $insertStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
        $insertStatement->bindParam(':recipientID', $recipientID, PDO::PARAM_INT);
        $insertStatement->bindParam(':content', $messageContent, PDO::PARAM_STR);

        // Executing the query
        try {
            $insertStatement->execute();
            // Redirecting to Pms.php after sending the private message
            header('Location: Pms.php');
            exit();
        } catch (PDOException $e) {
            $createMessage = "<p class='error'> Error sending private message: </p>" . $e->getMessage();
        }
    } else {
        $createMessage = "<p class='error'> Cannot send message: Recipient user does not exist. </p>";
    }
}

// Fetching usernames and user IDs based on the input query
$userData = array();
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $searchQuery = "SELECT UserID, Username FROM UserAccount WHERE Username LIKE :query";
    $searchStatement = $pdo->prepare($searchQuery);
    $searchStatement->bindValue(':query', $query . '%', PDO::PARAM_STR); // Change the LIKE pattern to search for names starting with the query
    $searchStatement->execute();

    // Fetching usernames and user IDs and returning as JSON
    $userData = $searchStatement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($userData);
    exit(); // Terminating script after sending JSON data
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Including Bootstrap CSS from CDN -->

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) (From a CDN) -->
    <!-- A content delivery network (CDN) is a network of interconnected servers that speeds up webpage loading for data-heavy applications. -->
    <!-- Source: https://aws.amazon.com/what-is/cdn/#:~:text=A%20content%20delivery%20network%20(CDN,loading%20for%20data%2Dheavy%20applications.) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <title>Send Private Messages</title>
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

        <h1>Send Private Message</h1>
    </header>

    <main class="flex-grow-1 d-flex flex-column">
        <!-- flex-grow-1, d-flex, flex-column: These are Bootstrap classes for styling the main content area. -->

        <?php if (isset($createMessage))
            echo $createMessage; ?>
        <!-- Displaying any error message if it's set -->

        <!-- Form for sending private messages -->
        <form autocomplete="off" method="post" action="">
            <!-- Disabling autocomplete to prevent browser suggestions -->

            <!-- Textarea for typing the message -->
            <label for="privateMessageContent">Type Message Here:</label>
            <textarea name="privateMessageContent" rows="3" required></textarea><br>

            <!-- Label for selecting the recipient -->
            <label for="recipientUsername">Who would you like to send this message to:</label>
            <!-- Autocomplete feature -->
            <div class="autocomplete" style="width:300px;">
                <!-- Input field for entering recipient's username -->
                <input type="text" id="recipientUsername" name="recipientUsername" class="form-control" required
                    autocomplete="off">

                <!-- Hidden input field to store the recipient's ID -->
                <input type="hidden" id="recipientID" name="recipientID">

                <!-- Container to display autocomplete results -->
                <div id="autocompleteResults" class="autocomplete-items"></div>
            </div>

            <!-- Button to send the private message -->
            <input type="submit" name="sendPrivateMessage" value="Send Message">
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-2">
        <!-- bg-dark, text-white, text-center, py-2: These are Bootstrap classes for styling the footer. -->

        &copy; (Testing)
    </footer>

    <script>

        // Wait for the DOM content to be fully loaded before executing the code
        document.addEventListener('DOMContentLoaded', function () {
            // Get references to DOM elements
            const recipientInput = document.getElementById('recipientUsername'); // Input field for recipient's username
            const recipientIDInput = document.getElementById('recipientID'); // Hidden input field for recipient's ID
            const autocompleteResults = document.getElementById('autocompleteResults'); // Container for autocomplete results

            // Add event listener for input event on recipientInput
            recipientInput.addEventListener('input', function () {
                // Trim whitespace from the input value
                const query = recipientInput.value.trim();

                // If the input query is empty, clear autocomplete results and return
                if (query.length === 0) {
                    autocompleteResults.innerHTML = '';
                    return;
                }

                // Fetch autocomplete results from the server
                fetch(`<?php echo $_SERVER['PHP_SELF']; ?>?query=${query}`)
                    .then(response => response.json()) // Parse the response as JSON
                    .then(data => {
                        console.log(data); // Log the received data to the console for debugging
                        showAutocompleteResults(data); // Call the function to display autocomplete results
                    })
                    .catch(error => {
                        console.error('Error fetching autocomplete results:', error); // Log any errors to the console
                    });
            });

            // Function to display autocomplete results
            function showAutocompleteResults(results) {
                autocompleteResults.innerHTML = ''; // Clear previous autocomplete results

                // If there are no results, hide autocompleteResults and return
                if (results.length === 0) {
                    autocompleteResults.style.display = 'none';
                    return;
                }

                // Create a new div element to hold the autocomplete results
                const resultList = document.createElement('div');

                // Iterate over each result and create a div element for each one
                results.forEach(result => {
                    const item = document.createElement('div');
                    item.textContent = result.Username; // Set the text content of the div to the username

                    // Add event listener for click event on each result item
                    item.addEventListener('click', function () {
                        recipientInput.value = result.Username; // Set the value of the recipient input field to the selected username
                        recipientIDInput.value = result.UserID; // Set the value of the hidden input field to the selected user's ID
                        autocompleteResults.innerHTML = ''; // Clear autocomplete results
                    });

                    // Append the result item to the resultList
                    resultList.appendChild(item);
                });

                // Append the resultList to the autocompleteResults container
                autocompleteResults.appendChild(resultList);

                // Make the autocompleteResults container visible
                autocompleteResults.style.display = 'block';
            }
        });

    </script>

</body>

</html>