<?php
$query = $con->prepare('SELECT count(*)as `count` FROM `likes` WHERE `likes`.`video_id` = ?');
$query->bind_param('i', $id);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<p>$row[count]</p>";
} else {
    echo "<p>0</p>";
}
