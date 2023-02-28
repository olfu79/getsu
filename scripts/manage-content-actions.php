<?php
require_once 'isloggedin.php';
require_once 'isadmin.php';
require_once 'db_con.php';
if (!empty($_GET["s"])) {
    if (!empty($_GET['action']) && $_GET['action'] == "hide") {
        $hideAllEpisodes_query = "UPDATE `episodes` SET `isActive`='0' WHERE `series_id` = '$_GET[s]'";
        $hide_query = "UPDATE `series` SET `isActive`='0' WHERE `id` = '$_GET[s]'";
        if ($con->query($hide_query) && $con->query($hideAllEpisodes_query)) {
            header('Location: ../manage-content.php?s=shidden');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "show") {
        $showAllEpisodes_query = "UPDATE `episodes` SET `isActive`='1' WHERE `series_id` = '$_GET[s]'";
        $show_query = "UPDATE `series` SET `isActive`='1' WHERE `id` = '$_GET[s]'";
        if ($con->query($show_query) && $con->query($showAllEpisodes_query)) {
            header('Location: ../manage-content.php?s=sshown');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "edit") {
        header('Location: ../edit-content.php?s=' . $_GET["s"]);
        exit;
    }
    if (!empty($_GET['action']) && $_GET['action'] == "delete") {
        $delete_query = "DELETE FROM `series` WHERE `id` = '$_GET[s]'";
        if ($con->query($delete_query)) {
            header('Location: ../manage-content.php?s=sdeleted');
            exit;
        }
    }
}
if (!empty($_GET["e"])) {
    if (!empty($_GET['action']) && $_GET['action'] == "hide") {
        $hide_query = "UPDATE `episodes` SET `isActive`='0' WHERE `id` = '$_GET[e]'";
        if ($con->query($hide_query)) {
            header('Location: ../manage-content.php?s=ehidden');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "show") {
        $show_query = "UPDATE `episodes` SET `isActive`='1' WHERE `id` = '$_GET[e]'";
        if ($con->query($show_query)) {
            header('Location: ../manage-content.php?s=eshown');
            exit;
        }
    }
    if (!empty($_GET['action']) && $_GET['action'] == "edit") {
        header('Location: ../edit-content.php?e=' . $_GET["e"]);
        exit;
    }
    if (!empty($_GET['action']) && $_GET['action'] == "delete") {
        $delete_query = "DELETE FROM `episodes` WHERE `id` = '$_GET[e]'";
        if ($con->query($delete_query)) {
            header('Location: ../manage-content.php?s=edeleted');
            exit;
        }
    }
}
