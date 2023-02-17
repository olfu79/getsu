<?php
include 'scripts/isloggedin.php';
include 'scripts/db_con.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/reports.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <title>Getsu</title>
</head>

<body>
    <div class="wrapper">
        <div class="left-pane">
            <div class="logo">
                <a href="index.php">
                    <img src="logo/getsu.png" alt="logo" draggable="false" />
                </a>
            </div>
            <hr>
            <div class="navbar-left">
                <a href="index.php">
                    <span class="mdi mdi-compass"></span>Strona Główna
                </a>
                <a href="javascript:void(0);" class="dropdown">
                    <span class="mdi mdi-format-list-bulleted-square"></span>Lista anime
                </a>
                <div class="dropdown-container">
                    <?php
                    $query = "SELECT `id`, `alt_title`, `brd-start` FROM `series` WHERE `isActive` = '1' AND `brd-start` <= NOW() ORDER BY `alt_title` ASC";
                    $result = $con->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<a href='series.php?s=$row[id]'>$row[alt_title]</a>";
                    }
                    $result->free();
                    ?>
                </div>
                <a href="watchlist.php">
                    <span class="mdi mdi-playlist-play"></span>Do obejrzenia
                </a>
                <a href="coming_soon.php">
                    <span class="mdi mdi-calendar-clock"></span>Nadchodzące!
                </a>
                <?php if ($_SESSION['role'] == "admin") {
                    echo <<< ADMIN_SECTION
                    <hr>
                    <a href="add_item.php">
                        <span class="mdi mdi-plus"></span>Dodaj
                    </a>
                    <a class="active" href="reports.php">
                        <span class="mdi mdi-flag"></span>Zgłoszenia
                    </a>
                    <a href="manage-content.php">
                        <span class="mdi mdi-view-dashboard-edit"></span>Zarządzaj zawartością
                    </a>
                    <a href="manage-users.php">
                        <span class="mdi mdi-account-edit"></span>Zarządzaj użytkownikami
                    </a>
ADMIN_SECTION;
                }
                ?>
            </div>
            <hr>
            <div class="logout">
                <a href="scripts/logout.php">
                    <span class="mdi mdi-logout"></span>Log Out
                </a>
            </div>
        </div>
        <div class="pane-divider"></div>
        <div class="right-pane">
            <div class="nav-top">
                <div class="nav-top-left">
                    <a onclick="history.back()" class="nav-top-back"><span class="mdi mdi-chevron-left"></span></a>
                    <a onclick="history.forward()" class="nav-top-forward"><span class="mdi mdi-chevron-right"></span></a>
                    <form method="GET" action="search.php">
                        <input type="search" name="search" placeholder="Wyszukaj...">
                    </form>
                    <a onclick="" class="nav-top-filter"><span class="mdi mdi-filter-variant"></span></a>
                </div>
                <div class="nav-top-right">
                    <a href="index.php"><span class="mdi mdi-bell"></span></a>
                    <a href="profile.php"><span class="mdi mdi-account-circle"></a>
                </div>
            </div>
            <div class="main flex-column">
                <h1>Zgłoszone komentarze</h1>
                <div class="browse-section">
                    <?php
                    $reportedCommentsData_query = "SELECT `comments_reports`.`id`,`comments_reports`.`user_id`,`reported`.`username` as user_username,`comments_reports`.`comment_id`,`comments_reports`.`reason`,`comments_reports`.`note`,`comments_reports`.`reported_by`,`reportedby`.`username` as reported_by_username, `comments`.`content`, `comments`.`video_id`
                    FROM `comments_reports` 
                    INNER JOIN `accounts` as `reported` ON `reported`.`id` = `comments_reports`.`user_id`
                    INNER JOIN `comments` ON `comments`.`id` = `comments_reports`.`comment_id`
                    INNER JOIN `accounts` as `reportedby` ON `reportedby`.`id` = `comments_reports`.`reported_by`
                    ORDER BY `comments_reports`.`id`;";
                    $result = $con->query($reportedCommentsData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='comment-header'>
                                        <th>ID</th>
                                        <th>Zgłoszony</th>
                                        <th>Powód</th>
                                        <th>Komentarz</th>
                                        <th>Notatka</th>
                                        <th>Przez</th>
                                        <th class='actions-header'>Akcje</th>
                                    </tr>";
                        while ($commentsRow = $result->fetch_assoc()) {
                            $output .= "<tr class='comment-row' data-comment-id='$commentsRow[comment_id]'>
                                            <td class='comment_repId'>{$commentsRow["id"]}</td>
                                            <td class='comment_reported'><a href='profile.php?u={$commentsRow["user_id"]}'>{$commentsRow["user_username"]}</a></td>
                                            <td class='comment_reason'>{$commentsRow["reason"]}</td>
                                            <td class='comment_content'>{$commentsRow["content"]}</td>
                                            <td class='comment_note'>{$commentsRow["note"]}</td>
                                            <td class='comment_reportedBy'><a href='profile.php?u={$commentsRow["reported_by"]}'>{$commentsRow["reported_by_username"]}</a></td>
                                            <td class='comment_actions'>
                                                <div class='actions flex v-mid'>
                                                    <a href='watch.php?v={$commentsRow["video_id"]}#comments'><span class='mdi mdi-open-in-new'></span></a>
                                                    <a href='scripts/manage-reports-actions.php?action=delete-comment&id={$commentsRow["comment_id"]}'><span class='mdi mdi-comment-remove'></span></a>
                                                    <a href='scripts/manage-reports-actions.php?action=delete-report&id={$commentsRow["id"]}'><span class='mdi mdi-trash-can-outline'></span></a>
                                                    <a href='scripts/manage-reports-actions.php?action=ban&u={$commentsRow["user_id"]}&reason=komentarz/{$commentsRow["reason"]}'><span class='mdi mdi-account-cancel'></span></a>
                                                </div>
                                            </td>
                                        </tr>";
                        }
                        $output .= "</table>";
                    } else {
                        $output = "Nie znaleziono żadnych zgłoszeń.";
                    }
                    echo $output;
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    </script>
    <script src="scripts/dropdown.js"></script>
</body>

</html>