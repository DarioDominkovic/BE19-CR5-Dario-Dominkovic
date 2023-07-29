<?php
session_start();

if (isset($_GET['logout'])) { // if a parameter logout is in the URL (?logout)
   unset($_SESSION['user']); // removing the value from the session user
   unset($_SESSION['adm']); // removing the value from the session adm
   session_unset(); // removing the value from all sessions
   session_destroy(); // destroying the session completely
   header("Location: login.php"); // Redirect to login page after logout
   exit; // Optional: Ensure the script terminates after the redirect
}
