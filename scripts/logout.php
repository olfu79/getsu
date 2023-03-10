<?php
session_start();
if (isset($_COOKIE['remember'])) {
    setcookie('remember', '0', time() - 1, '/');
}

session_destroy();
header('Location: ../login.php');
