<?php 
// Logout.php

/*Notes
This page has a simple role. All it does is logs out the user if the user is logged in.*/

// Starts the session
session_start();

// Unsets all session variables
$_SESSION = array();

// Destroys the session
session_destroy();

// Redirects to the login page
header('Location: Signin_up.php');
exit();
?>