<?php
session_start();
include 'db_con.php';

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'rok', 'm' => 'miesiąc', 'w' => 'tygodnie', 'd' => 'dni', 'h' => 'godzin', 'i' => 'minut', 's' => 'sekund');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v; // . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' temu' : 'teraz';
}
function show_comments($comments, $parent_id = -1)
{
    $html = '';
    if (empty($comments)) {
        $html .= "<p class='no-comments'>Brak komentarzy</p>";
    } else {
        if ($parent_id != -1) {
            array_multisort(array_column($comments, 'submit_date'), SORT_ASC, $comments);
        }
        foreach ($comments as $comment) {
            include 'db_con.php';
            $getUsername_query = "SELECT `username` FROM `accounts` WHERE `id` = '$comment[author_id]'";
            $result = $con->query($getUsername_query);
            $res = $result->fetch_assoc();
            if ($comment['parent_id'] == $parent_id) {
                $html .= '
                <div class="comment" id="' . $comment['id'] . '">
                    <div>
                        <a href="profile.php?u=' . $comment['author_id'] . '"><h3 class="name">' . htmlspecialchars($res['username'], ENT_QUOTES) . '</h3></a>
                        <span class="date">' . time_elapsed_string($comment['submit_date']) . '</span>
                    </div>
                    <p class="content">' . nl2br(htmlspecialchars($comment['content'], ENT_QUOTES)) . '</p>
                    <a class="reply_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Odpowiedz</a>
                    <a class="report_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Zgłoś</a>
                    ' . show_write_comment_form($comment['id']) . '
                    <div class="replies">
                    ' . show_comments($comments, $comment['id']) . '
                    </div>
                </div>
                ';
            }
        }
    }
    return $html;
}
function show_write_comment_form($parent_id = -1)
{
    $html = '
    <div class="write_comment" data-comment-id="' . $parent_id . '">
        <form>
            <input name="parent_id" type="hidden" value="' . $parent_id . '">
            <input type="text" name="content" placeholder="Napisz odpowiedź..." required>
        </form>
    </div>
    ';
    return $html;
}
if (isset($_GET['video_id'])) {
    if (isset($_SESSION['id'], $_POST['content'])) {
        $insertComment_query = "INSERT INTO comments (video_id, parent_id, author_id, content, submit_date) VALUES ('$_GET[video_id]','$_POST[parent_id]','$_SESSION[id]','$_POST[content]',NOW())";
        $result = $con->query($insertComment_query);
        exit('Przesłano komentarz!');
    }
    $getAllComments_query = "SELECT * FROM comments WHERE video_id = '$_GET[video_id]' ORDER BY submit_date DESC";
    $result = $con->query($getAllComments_query);
    $comments = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
} else {
    exit('Nie sprecyzowano id video!');
}
?>
<div class="comment_header">
    <div class="write_comment first-comment" data-comment-id="-1">
        <form>
            <input name="parent_id" type="hidden" value="-1">
            <input type="text" name="content" placeholder="Napisz komentarz..." required="">
        </form>
    </div>

</div>

<?= show_write_comment_form() ?>

<?= show_comments($comments) ?>