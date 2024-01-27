<!--Name: Solomon Thomas -->

<!--Class: CPS 3351 -->

<!--Group Project: Phase V (Phase 5)-->

<!--Notes-->
<!--This page has a simple role. All it does is logs out the user if the user is logged in.-->

<?php // Logout.php

// Starts the session
session_start();

// Unsets all session variables
$_SESSION = array();

// Destroys the session
session_destroy();

// Redirects to the login page
header('Location: Login.php');
exit();
?>