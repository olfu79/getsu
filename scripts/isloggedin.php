<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200) && (empty($_COOKIE['remember']) || $_COOKIE['remember'] == 0)) {
    // Destroy the session
    session_unset();
    session_destroy();
    // Redirect to the login page
    header('Location: login.php');
    exit;
}

$_SESSION['last_activity'] = time();
