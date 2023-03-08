<?php
include 'db_con.php';
$user_id = $_POST['uid'];
$avatar = isset($_POST['avatar']) ? $_POST['avatar'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$query = "UPDATE `accounts` SET `description`='$description', `avatar`='$avatar' WHERE `id`='$user_id'";
$result = $con->query($query);
if ($result) {
    echo "success";
} else {
    echo "error";
}
