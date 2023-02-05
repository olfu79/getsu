<?php
if (isset($_GET['s'])) {
    $sId = $_GET['s'];

    $series_query = "SELECT * FROM `series` WHERE `id` = '$sId'";
    $result = $con->query($series_query);
    if ($result->num_rows == 0) {
        header('Location: error.php?e=nie ma takiej serii');
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
    header('Location: error.php?e=nie podano nawet id serii');
    exit;
}
