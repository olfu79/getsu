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
    <link rel="stylesheet" href="style/profile.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <link rel="icon" type="image/png" href="logo/favicon.png" />

    <link href="node_modules/noty/lib/noty.css" rel="stylesheet">
    <link href="node_modules/noty/lib/themes/relax.css" rel="stylesheet">
    <script src="node_modules/noty/lib/noty.js" type="text/javascript"></script>
    <script type="text/javascript" src="scripts/notifications.js"></script>

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
                    <span class="mdi mdi-compass"></span><span class="menu-title">Strona Główna</span>
                </a>
                <a href="javascript:void(0);" class="dropdown">
                    <span class="mdi mdi-format-list-bulleted-square"></span><span class="menu-title">Lista anime</span>
                </a>
                <div class="dropdown-container">
                    <?php
                    $query = "SELECT `id`, `alt_title`, `brd-start` FROM `series` WHERE `isActive` = '1' AND `brd-start` <= NOW() ORDER BY `alt_title` ASC";
                    $result = $con->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<a href='series.php?s=$row[id]' id='$row[id]'>$row[alt_title]</a>";
                    }
                    $result->free();
                    ?>
                </div>
                <a href="watchlist.php">
                    <span class="mdi mdi-playlist-play"></span><span class="menu-title">Do obejrzenia</span>
                </a>
                <a href="coming_soon.php">
                    <span class="mdi mdi-calendar-clock"></span><span class="menu-title">Nadchodzące!</span>
                </a>
                <?php if ($_SESSION['role'] == "admin" || $_SESSION['role'] == "mod") {
                    echo <<< ADMIN_SECTION
                    <hr>
                    <a href="add_item.php">
                        <span class="mdi mdi-plus"></span><span class="menu-title">Dodaj</span>
                    </a>
                    <a href="reports.php">
                        <span class="mdi mdi-flag"></span><span class="menu-title">Zgłoszenia</span>
                    </a>
                    <a href="manage-content.php">
                        <span class="mdi mdi-view-dashboard-edit"></span><span class="menu-title">Zarządzaj zawartością</span>
                    </a>
                    <a href="manage-users.php">
                        <span class="mdi mdi-account-edit"></span><span class="menu-title">Zarządzaj użytkownikami</span>
                    </a>
ADMIN_SECTION;
                }
                ?>
            </div>
            <hr>
            <div class="logout">
                <a href="https://discord.gg/nQb7GDaPyn" target="_blank">
                    <span class="mdi mdi-message"></span><span class="menu-title">Kontakt</span>
                </a>
                <hr>
                <a href="scripts/logout.php">
                    <span class="mdi mdi-logout"></span><span class="menu-title">Log out</span>
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
                    <a href="index.php" class="nav-top-notifications"><span class="mdi mdi-bell"></span></a>
                    <a href="profile.php" class="nav-top-profile"><span class="mdi mdi-account-circle"></a>
                </div>
            </div>
            <div class="main">
                <div class="profile-wrapper">
                    <?php
                    if (empty($_GET['u']) || $_GET['u'] == $_SESSION['id']) {
                        $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = '$_SESSION[id]'";
                        $result = $con->query($getUserData_query);
                        $res = $result->fetch_assoc();
                        $rawDesc = nl2br(htmlspecialchars($res['description']));
                        if (empty(trim($rawDesc))) {
                            $rawDesc = "Brak opisu.";
                        }
                        $avatar = "<img class='pfp' src='resources/default.jpg' alt='User Avatar'>";
                        if ($res['avatar'] != "") {
                            $avatar = '<img class="pfp" src="' . $res['avatar'] . '" alt="User Avatar">';
                        }

                        //stats
                        $result = $con->query("SELECT COUNT(*)as `ilosc_kom` FROM `comments` WHERE `author_id` = '$res[id]';")->fetch_assoc();
                        $comments_count = $result['ilosc_kom'];
                        $result = $con->query("SELECT COUNT(*) as `ilosc_like` FROM `likes` WHERE `user_id` = '$res[id]';")->fetch_assoc();
                        $likes_count = $result['ilosc_like'];
                        $result = $con->query("SELECT `alt_title`, `likes`, `episode_count`, (`likes`/`episode_count`) AS `likes_per_episode`
                    FROM (
                      SELECT `series`.`alt_title`, count(`likes`.`user_id`) AS `likes`, 
                      (SELECT count(`id`) FROM `episodes` WHERE `series_id` = `series`.`id`) AS `episode_count`
                      FROM `likes` 
                      INNER JOIN `episodes` on `episodes`.`id` = `likes`.`video_id`
                      INNER JOIN `series` on `series`.`id` = `episodes`.`series_id`
                      WHERE `likes`.`user_id` = $res[id]
                      GROUP BY `series`.`alt_title`
                    ) subquery
                    ORDER BY `likes_per_episode` DESC
                    LIMIT 1;
                    ")->fetch_assoc();
                        if ($result && $result['alt_title']) {
                            $favourite_series = $result['alt_title'];
                        } else {
                            $favourite_series = "Brak.";
                        }
                        $role = 'użytkownik';
                        if ($res['role'] == "mod") {
                            $role = 'moderator';
                        }
                        if ($res['role'] == "admin") {
                            $role = 'administrator';
                        }
                        $result = $con->query("SELECT `id`, `reg_date`, DATEDIFF(CURDATE(), `reg_date`) AS `days_since_registration` FROM `accounts` WHERE `id` = '$res[id]';")->fetch_assoc();
                        $days_from_register = $result['days_since_registration'];
                        $reg_date = $res['reg_date'];

                        echo <<< USER_DATA
                        <div class="user-card flex">
                            <div class="left flex flex-column">
                                $avatar
                                <span class="data-username"><b>$res[username]</b></span>
                                <span class="data-role"><b>$role</b></span>
                            </div>
                            <div class="mid flex flex-column">
                                $rawDesc
                            </div>
                        </div>
                        <div class="right flex flex-column">
                                <h2>Statystyki</h2>
                                <p>Ilość komentarzy: <span class="stats-data">$comments_count</span></p>
                                <p>Ilość polubień: <span class="stats-data">$likes_count</span></p>
                                <p>Ulubiona seria: <span class="stats-data">$favourite_series</span></p>
                                <p>Data rejestracji <span class="stats-data">$reg_date</span></p>
                                <p>Dni od rejestracji: <span class="stats-data">$days_from_register</span></p>
                        </div>
USER_DATA;
                    } else {
                        $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = '$_GET[u]'";
                        $result = $con->query($getUserData_query);
                        if ($result->num_rows == 0) {
                            header('Location: profile.php?e=usernotexist');
                            exit;
                        } else {
                            $res = $result->fetch_assoc();
                            $rawDesc = nl2br(htmlspecialchars($res['description']));
                            if (empty(trim($rawDesc))) {
                                $rawDesc = "Brak opisu.";
                            }
                            $avatar = "<img class='pfp' src='resources/default.jpg' alt='User Avatar'>";
                            if ($res['avatar'] != "") {
                                $avatar = '<img class="pfp" src="' . $res['avatar'] . '" alt="User Avatar">';
                            }
                            //stats
                            $result = $con->query("SELECT COUNT(*)as `ilosc_kom` FROM `comments` WHERE `author_id` = '$res[id]';")->fetch_assoc();
                            $comments_count = $result['ilosc_kom'];
                            $result = $con->query("SELECT COUNT(*) as `ilosc_like` FROM `likes` WHERE `user_id` = '$res[id]';")->fetch_assoc();
                            $likes_count = $result['ilosc_like'];
                            $result = $con->query("SELECT `alt_title`, `likes`, `episode_count`, (`likes`/`episode_count`) AS `likes_per_episode`
                            FROM (
                            SELECT `series`.`alt_title`, count(`likes`.`user_id`) AS `likes`, 
                            (SELECT count(`id`) FROM `episodes` WHERE `series_id` = `series`.`id`) AS `episode_count`
                            FROM `likes` 
                            INNER JOIN `episodes` on `episodes`.`id` = `likes`.`video_id`
                            INNER JOIN `series` on `series`.`id` = `episodes`.`series_id`
                            WHERE `likes`.`user_id` = $res[id]
                            GROUP BY `series`.`alt_title`
                            ) subquery
                            ORDER BY `likes_per_episode` DESC
                            LIMIT 1;
                            ")->fetch_assoc();
                            if ($result && $result['alt_title']) {
                                $favourite_series = $result['alt_title'];
                            } else {
                                $favourite_series = "Brak.";
                            }
                            $role = 'użytkownik';
                            if ($res['role'] == "mod") {
                                $role = 'moderator';
                            }
                            if ($res['role'] == "admin") {
                                $role = 'administrator';
                            }
                            $result = $con->query("SELECT `id`, `reg_date`, DATEDIFF(CURDATE(), `reg_date`) AS `days_since_registration` FROM `accounts` WHERE `id` = '$res[id]';")->fetch_assoc();
                            $days_from_register = $result['days_since_registration'];
                            $reg_date = $res['reg_date'];

                            echo <<< USER_DATA
                        <div class="user-card flex">
                            <div class="left flex flex-column">
                                $avatar
                                <span class="data-username"><b>$res[username]</b></span>
                                <span class="data-role"><b>$role</b></span>
                            </div>
                            <div class="mid flex flex-column">
                                $rawDesc
                            </div>
                        </div>
                        <div class="right flex flex-column">
                            <h2>Statystyki</h2>
                            <p>Ilość komentarzy: <span class="stats-data">$comments_count</span></p>
                            <p>Ilość polubień: <span class="stats-data">$likes_count</span></p>
                            <p>Ulubiona seria: <span class="stats-data">$favourite_series</span></p>
                            <p>Data rejestracji <span class="stats-data">$reg_date</span></p>
                            <p>Dni od rejestracji: <span class="stats-data">$days_from_register</span></p>
                        </div>
    USER_DATA;
                        }
                    }
                    ?>
                </div>
                <?php
                if (empty($_GET['u']) || $_GET['u'] == $_SESSION['id']) echo "<a class='edit' href='edit-profile.php'>Edytuj</a>";
                ?>
            </div>
        </div>
    </div>
    <script src="scripts/dropdown.js"></script>
</body>

</html>