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
                    $query = "SELECT `id`, `alt_title` FROM `series` WHERE `isActive` = '1' ORDER BY `alt_title` ASC";
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
                    <a href="reports.php">
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
                    <input type="search" name="search" placeholder="Search...">
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
                    $reportedCommentsData_query = "SELECT `comments_reports`.`id`,`comments_reports`.`user_id`,`reported`.`username` as user_username,`comments_reports`.`comment_id`,`comments_reports`.`reason`,`comments_reports`.`note`,`comments_reports`.`reported_by`,`reportedby`.`username` as reported_by_username, `comments`.`content` 
                    FROM `comments_reports` 
                    INNER JOIN `accounts` as `reported` ON `reported`.`id` = `comments_reports`.`user_id`
                    INNER JOIN `comments` ON `comments`.`id` = `comments_reports`.`comment_id`
                    INNER JOIN `accounts` as `reportedby` ON `reportedby`.`id` = `comments_reports`.`reported_by`
                    ORDER BY `comments_reports`.`id`
                    ";
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
                                            <td class='comment_reported'>{$commentsRow["user_username"]}</td>
                                            <td class='comment_reason'>{$commentsRow["reason"]}</td>
                                            <td class='comment_content'>{$commentsRow["content"]}</td>
                                            <td class='comment_note'>{$commentsRow["note"]}</td>
                                            <td class='comment_reportedBy'>{$commentsRow["reported_by_username"]}</td>
                                            <td class='comment_actions'>
                                                <div class='actions flex v-mid'>
                                                    <a href='scripts/manage-reports-actions.php?u=&action=delete'><span class='mdi mdi-trash-can-outline'></span></a>
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