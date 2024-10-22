<?php

// Start the session
session_start();

// Unsetting all session variables
$_SESSION = array();

// Destroy the session
if (ini_get("session.use_cookies")) {

   // Get session parameters
   $params = session_get_cookie_params();

   // Delete the session cookie
   setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
   );

};

// Finally, destroy the session
session_destroy();

// Redirect to the homr page
header("Location: ../index.php");

exit();
