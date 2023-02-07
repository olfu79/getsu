<?php
include 'db_con.php';
do {
    $uid = substr(abs(crc32(uniqid())), 0, 9);
    if (strlen($uid) < 9) {
        continue;
    }
    $query = "SELECT * FROM `series` WHERE `id`='$uid'";
    $result = $con->query($query);
} while ($result->num_rows > 0 || strlen($uid) != 9);
echo $uid;
