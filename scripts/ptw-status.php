<?php
$query = $con->prepare('SELECT * FROM `plan_to_watch` WHERE `plan_to_watch`.`series_id` = ? AND `plan_to_watch`.`user_id` = ?');
$query->bind_param('ii', $series_id, $_SESSION['id']);
$query->execute();
$result = $query->get_result();
if ($result->num_rows > 0) {
    echo "<span class='mdi mdi-playlist-check ptw-button' data-series-id='$series_id'></span>";
} else {
    echo "<span class='mdi mdi-playlist-plus ptw-button' data-series-id='$series_id'></span>";
}
