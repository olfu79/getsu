<?php
include 'db_con.php';
do {
    $uid = substr(abs(crc32(uniqid())), 0, 9);
    $query = "SELECT * FROM `series` WHERE `id`='$uid'";
    $result = $con->query($query);
} while ($result->num_rows > 0);
echo $uid;
