<?php
session_start();
if (isset($_COOKIE['remember'])) {
    setcookie('remember', '0', time() - 1, '/');
}
if (isset($_SESSION['fblogout'])) {
    session_destroy();
    header('Location: ' . $_SESSION['fblogout']);
    exit;
}
session_destroy();
header('Location: ../login.php');
