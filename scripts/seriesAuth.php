<?php
if (isset($_GET['s'])) {
    $sId = $_GET['s'];

    $series_query = "SELECT * FROM `series` WHERE `id` = '$sId' AND `isActive` = 1";
    $result = $con->query($series_query);
    if ($result->num_rows == 0) {
        header('Location: index.php?e=seriesnotexist');
        exit;
    } else {
        $res = $result->fetch_assoc();
        $series_id = $res['id'];
        $title = $res['title'];
        $alt_title = $res['alt_title'];
        $season = $res['season'];
        $poster = $res['poster'];
        $desc = $res['desc'];
        $genre = $res['genre'];
        $brdType = $res['brd-type'];
        $brdStart = $res['brd-start'];
        $brdEnd = $res['brd-end'];
        $epCount = $res['ep_count'];
    }
    $result->free();
} else {
    header('Location: index.php?e=error');
    exit;
}
