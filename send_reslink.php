<?php
// send_reslink.php

/*Notes
Allows for the user to be sent a link to reset their password 
The plan is for this to send the user an email.
The email will contain information about what to do and a link where they can reset their password.
*/


include "dbcon.php"; // Include your database connection code

// Include PHPMailer autoload file
require 'PHPMailer-6.9.1/src/PHPMailer.php';
require 'PHPMailer-6.9.1/src/SMTP.php';
require 'PHPMailer-6.9.1/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Retrieve the user's email associated with the username from the database
    $query = "SELECT UserID, Email FROM UserAccount WHERE Username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Generate a unique token for the password reset link
        $token = generateToken();

        // Calculate expiration time (e.g., 1 hour from now)
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert the token into the PasswordReset table
        $query = "INSERT INTO PasswordReset (UserID, Token, Expiration) VALUES (:userID, :token, :expiration)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userID', $user['UserID'], PDO::PARAM_INT);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expiration', $expiration, PDO::PARAM_STR);
        $stmt->execute();

        // Send the password reset link to the user's email
        
        /*This needs to be the link to the site: Example: 
        http://localhost/CPS%204951-ChatPage-Test/reset_password.php  (May need: ?token=  at the end of the address)*/
        $resetLink = "http://localhost/CPS%204951-ChatPage-Test/reset_password.php?token=" . $token;

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Your SMTP server address provided by 000webhost
            $mail->SMTPAuth = true;
            $mail->Username = 'your_username'; // Your SMTP username provided by 000webhost
            $mail->Password = 'your_password'; // Your SMTP password provided by 000webhost
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // Your SMTP port number provided by 000webhost

            // Recipients
            $mail->setFrom('your_email@example.com', 'Your Name');
            $mail->addAddress($user['Email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click the following link to reset your password: $resetLink";

            // Send email
            $mail->send();
            echo "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Failed to send password reset link. Please try again later.";
        }
    } else {
        echo "Username not found. Please enter a valid username.";
    }
}

// Function to generate a unique token (you can use any method you prefer)
function generateToken() {
    return bin2hex(random_bytes(32));
}


// Retrieve the user's email associated with the username from the database
    // If the username exists, proceed with sending the password reset link to the user's email
    // You can use PHP's mail() function or a library like PHPMailer to send the email

     
?>

