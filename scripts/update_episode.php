<?php
require_once('db_con.php');
if (empty($_POST['id']) || empty($_POST['series']) || empty($_POST['title']) || empty($_POST['number']) || empty($_POST['url']) || empty($_POST['poster'])) {
    header('Location: ../manage-content.php?e=edatamissing');
    exit;
} else {
    $checkIfExist_query = "SELECT * FROM `episodes` WHERE `episodes`.`id` = '$_POST[id]' OR (`episodes`.`series_id` = '$_POST[series]' AND `episodes`.`ep_number` = '$_POST[number]')";
    $result = $con->query($checkIfExist_query);
    if ($result->num_rows == 0) {
        header('Location: ../manage-content.php?e=episodenotexist');
        exit;
    }
    $isActive = ($_POST['visible'] == 'on') ? true : false;
    $introEnd = (60 * intval($_POST['minutes'])) + intval($_POST['seconds']);
    $addEpisode_query = "UPDATE `episodes` 
                            SET `series_id` = '$_POST[series]', 
                                `url` = '$_POST[url]', 
                                `poster` = '$_POST[poster]', 
                                `title` = '$_POST[title]', 
                                `ep_number` = '$_POST[number]', 
                                `isActive` = '$isActive', 
                                `desc` = '$_POST[desc]', 
                                `intro_end` = '$introEnd' 
                            WHERE `id` = '$_POST[id]'";
    if ($con->query($addEpisode_query)) {
        header('Location: ../manage-content.php?s=eupdated');
        exit;
    } else {
        header('Location: ../manage-content.php?e=error');
        exit;
    }
}
