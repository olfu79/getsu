<?php
require_once('db_con.php');
if (empty($_POST['id']) || empty($_POST['altname']) || empty($_POST['fullname']) || empty($_POST['season']) || empty($_POST['epcount']) || empty($_POST['brdtype']) || empty($_POST['brdstart']) || empty($_POST['brdend']) || empty($_POST['genre']) || empty($_POST['desc']) || empty($_POST['poster'])) {
    header('Location: error.php?e=brakuje danych ser');
    exit;
} else {
    $genres = $_POST['genre'];
    $genre_string = "";
    foreach ($genres as $genre) {
        $genre_string .= $genre . "; ";
    }
    $addSeries_query = "UPDATE `series` SET 
                                `title` = '$_POST[fullname]', 
                                `alt_title` = '$_POST[altname]', 
                                `season` = '$_POST[season]', 
                                `poster` = '$_POST[poster]', 
                                `desc` = '$_POST[desc]', 
                                `genre` = '$genre_string', 
                                `brd-type` = '$_POST[brdtype]', 
                                `brd-start` = '$_POST[brdstart]', 
                                `brd-end` = '$_POST[brdend]', 
                                `ep_count` = $_POST[epcount], 
                                `tags` = '$_POST[tags]'
                                WHERE `id` = '$_POST[id]'
                                ";
    if ($con->query($addSeries_query)) {
        header('Location: ../manage-content.php?s=sucupdt ser');
        exit;
    } else {
        header('Location: ../manage-content.php?e=error');
        exit;
    }
}
