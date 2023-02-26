<?php
if (isset($_GET['v'])) {
    $vId = $_GET['v'];
    $episode_query = "SELECT * FROM `episodes` WHERE `id` = '$vId' AND `isActive` = 1";
    $result = $con->query($episode_query);
    if ($result->num_rows == 0) {
        header('Location: index.php?e=episodenotexist');
        exit;
    }
}
