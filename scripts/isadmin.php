<?php
if ($_SESSION['role'] != "admin" || $_SESSION['role'] != "mod") {
    header('Location: index.php');
    exit;
}
