<?php
session_start();
include 'db_con.php';

if (!isset($_POST['username'], $_POST['password'])) {
    header('Location: ../login.php?e=error');
    exit;
}
if ($stmt = $con->prepare('SELECT `id`, `password`, `role` FROM `accounts` WHERE `username` = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password, $role);
        $stmt->fetch();
        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;
            if ($_POST['remember'] == "on") {
                setcookie('remember', '1', time() + (7 * 24 * 60 * 60), '/');
            }
            header('Location: ../index.php');
        } else {
            // Incorrect password
            header('Location: ../login.php?e=wrngpass');
        }
    } else {
        // Incorrect username
        header('Location: ../login.php?e=wrngpass');
    }
    $stmt->close();
}
