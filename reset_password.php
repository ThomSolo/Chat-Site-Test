<?php
// reset_password.php

/*Notes
Allows for the user to reset their password 
The plan is for this to be the link that was sent.
Once cliked, they will be directed to this page and change their password.
*/

include "dbcon.php"; // Include your database connection code


// Check if the token is provided in the URL
if (!isset($_GET['token'])) {
    echo "Token not provided.";
    exit;
}

$token = $_GET['token'];

// Retrieve the user ID associated with the token from the database
$query = "SELECT UserID FROM PasswordReset WHERE Token = :token AND Expiration > NOW()";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':token', $token, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    echo "Invalid or expired token.";
    exit;
}

// Process the password update if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $query = "UPDATE UserAccount SET Password = :password WHERE UserID = :userID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':userID', $user['UserID'], PDO::PARAM_INT);
    $stmt->execute();

    // Delete the token from the PasswordReset table
    $query = "DELETE FROM PasswordReset WHERE Token = :token";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    echo "Password updated successfully. You can now <a href='login.php'>login</a> with your new password.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content goes here -->
</head>
<body>
    <h2>Reset Password</h2>
    <form action="" method="post">
        <label for="password">Enter your new password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>