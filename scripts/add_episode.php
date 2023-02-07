<?php
require_once('db_con.php');
if (empty($_POST['id']) || empty($_POST['series']) || empty($_POST['title']) || empty($_POST['number']) || empty($_POST['url']) || empty($_POST['poster'])) {
    header('Location: error.php?e=brakuje danych odc');
    exit;
} else {
    $checkIfExist_query = "SELECT * FROM `episodes` WHERE `episodes`.`id` = '$_POST[id]' OR (`episodes`.`series_id` = '$_POST[series]' AND `episodes`.`ep_number` = '$_POST[number]')";
    $result = $con->query($checkIfExist_query);
    if ($result->num_rows > 0) {
        header('Location: ../add_item.php?e=odcinek juz istnieje');
        exit;
    }
    $isActive = ($_POST['visible'] == 'on') ? true : false;
    $introEnd = (60 * intval($_POST['minutes'])) + intval($_POST['seconds']);
    $addEpisode_query = "INSERT INTO `episodes`(`id`, `series_id`, `url`, `poster`, `title`, `ep_number`, `isActive`, `desc`, `intro_end`) VALUES ('$_POST[id]','$_POST[series]','$_POST[url]','$_POST[poster]','$_POST[title]','$_POST[number]','$isActive','$_POST[desc]','$introEnd')";
    if ($con->query($addEpisode_query)) {
        header('Location: ../add_item.php?s=sucadded ep');
        exit;
    } else {
        header('Location: ../add_item.php?e=error');
        exit;
    }
}
