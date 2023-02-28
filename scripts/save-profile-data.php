<?php
include 'db_con.php';
$user_id = $_POST['uid'];
$avatar = isset($_FILES['file']) ? fopen($_FILES['file']['tmp_name'], 'rb') : null;
$description = isset($_POST['description']) ? $_POST['description'] : 'Brak opisu.';

$query = "";
if ($avatar) {
    $avatarData = fread($avatar, filesize($_FILES['file']['tmp_name']));
    $avatarData = addslashes($avatarData);
    $query = "UPDATE `accounts` SET `description`='$description', `avatar`='$avatarData' WHERE `id`='$user_id'";
    fclose($avatar);
} else {
    $query = "UPDATE `accounts` SET `description`='$description' WHERE `id`='$user_id'";
}
$result = $con->query($query);
if ($result) {
    echo "success";
} else {
    echo "error";
}
