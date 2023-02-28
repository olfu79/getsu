<?php
require_once 'isloggedin.php';
require_once 'isadmin.php';
require_once 'db_con.php';
if (!empty($_GET["action"])) {
    if (!empty($_GET['u']) && !empty($_GET['reason']) && $_GET['action'] == "ban") {
        $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = '$_GET[u]'";
        $result = $con->query($getUserData_query)->fetch_assoc();
        if ($result['role'] != "user") {
            header('Location: ../reports.php?e=cantbanadm');
            exit;
        }
        $ban_query = "INSERT INTO `banned`(`id`, `username`, `password`, `email`, `role`, `description`, `avatar`, `reg_date`, `reason`) VALUES ('$result[id]', '$result[username]', '$result[password]', '$result[email]', '$result[role]', '$result[description]', '$result[avatar]', '$result[reg_date]', '$_GET[reason]')";
        $removeUser_query = "DELETE FROM `accounts` WHERE `id` = '$_GET[u]'";
        if ($con->query($ban_query) && $con->query($removeUser_query)) {
            header('Location: ../reports.php?s=banned');
            exit;
        }
    }
    if (!empty($_GET['id']) && $_GET['action'] == "delete-comment") {
        $deleteComment_query = "DELETE FROM `comments` WHERE `id` = '$_GET[id]'";
        if ($con->query($deleteComment_query)) {
            header('Location: ../reports.php?s=delcom');
            exit;
        }
    }
    if (!empty($_GET['id']) && $_GET['action'] == "delete-report") {
        $deleteReport_query = "DELETE FROM `comments_reports` WHERE `id` = '$_GET[id]'";
        if ($con->query($deleteReport_query)) {
            header('Location: ../reports.php?s=delrep');
            exit;
        }
    }
}
