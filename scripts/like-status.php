<?php
$query = $con->prepare('SELECT * FROM `likes` WHERE `likes`.`video_id` = ? AND `likes`.`user_id` = ?');
$query->bind_param('ii', $id, $_SESSION['id']);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    echo "<span class='mdi mdi-heart like-button' data-video-id='$id'></span>";
} else {
    echo "<span class='mdi mdi-heart-outline like-button' data-video-id='$id'></span>";
}
