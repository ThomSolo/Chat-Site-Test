<!--Notes-->
<!--This file works with jquery (from Create.php). In this file, it uses an Ajax request and 
send the entered username to this file. Once sent, this file will check the SQL database we have
and make sure there is no duplicate name (no username that matches the wanted/entered username).-->

<?php // Checkuseravail.php
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

// Gets the entered username from the AJAX request
$username = isset($_POST['username']) ? $_POST['username'] : '';

// Checks if the username is available
$checkQuery = "SELECT COUNT(*) FROM UserAccount WHERE Username = :username";
$checkStatement = $pdo->prepare($checkQuery);
$checkStatement->bindParam(':username', $username, PDO::PARAM_STR);
$checkStatement->execute();
$count = $checkStatement->fetchColumn();

// Sends the response back to the AJAX request
if ($count > 0) {
    echo '<span style="color: red;">Username not available</span>';
} else {
    echo '<span style="color: green;">Username available</span>';
}
?>