<?php 
// Checkuseravail.php

/*Notes
This file works with jquery (from Create.php). In this file, it uses an Ajax request and 
sends the entered username to this file. Once sent, this file will check the SQL database we have
and make sure there is no duplicate name (no username that matches the wanted/entered username).*/

include "dbcon.php"; // Includes the database connection file

// Starts the session
session_start();

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Creates a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throws PDOException if connection fails
}

// Gets the entered username from the AJAX request
$username = isset($_POST['username']) ? $_POST['username'] : '';

// Checks if the username is available
$checkQuery = "SELECT COUNT(*) FROM UserAccount WHERE Username = :username"; // SQL query to count occurrences of the entered username
$checkStatement = $pdo->prepare($checkQuery); // Prepares the SQL statement
$checkStatement->bindParam(':username', $username, PDO::PARAM_STR); // Binds the parameter
$checkStatement->execute(); // Executes the SQL statement
$count = $checkStatement->fetchColumn(); // Fetches the result count

// Sends the response back to the AJAX request
if ($count > 0) {
    echo '<span style="color: red;">Username not available</span>'; // Echoes a message indicating that the username is not available
} else {
    echo '<span style="color: green;">Username available</span>'; // Echoes a message indicating that the username is available
}
?>
