<?php
include 'isloggedin.php';
include 'db_con.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment-id'];
    $getAuthor_query = "SELECT `author_id` FROM `comments` WHERE `id` = '$comment_id'";
    $result = $con->query($getAuthor_query);
    $res = $result->fetch_assoc();
    $author_id = $res['author_id'];
    $reason =  $_POST['reason'];
    $note =  $_POST['note'];;
    $insertReport_query = "INSERT INTO `comments_reports`(`user_id`, `comment_id`, `reason`, `note`, `reported_by`) VALUES ('$author_id','$comment_id', '$reason', '$note', '$_SESSION[id]')";
    $result = $con->query($insertReport_query);
}
