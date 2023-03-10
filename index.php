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
    <link rel="stylesheet" href="style/browse.css">
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
                <a class="active" href="index.php">
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
                        echo "<a href='series.php?s=$row[id]'>$row[alt_title]</a>";
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
            <div class="main flex-column">
                <?php
                $query1 = "SELECT * FROM `history` INNER JOIN `accounts` ON `accounts`.`id` = `history`.`id` WHERE `accounts`.`id` = '$_SESSION[id]'";
                $result1 = $con->query($query1);
                if ($result1->num_rows > 0) {
                    echo "<h1>Ostatnio oglądane</h1><div class='browse-section'>";
                    while ($row1 = $result1->fetch_assoc()) {
                        $episodes_list = explode(";", $row1['last_watched']);
                        $history_count = count($episodes_list) > 4 ? 4 : count($episodes_list);
                        for ($i = 0; $i < $history_count; $i++) {
                            $query2 = "SELECT `series`.`alt_title`, `episodes`.`poster`, `series`.`season`, `episodes`.`ep_number` FROM `episodes` INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id` WHERE `episodes`.`id`='$episodes_list[$i]' LIMIT 4";
                            if ($result2 = $con->query($query2)) {
                                while ($row2 = $result2->fetch_assoc()) {
                                    echo <<< CONTENT
                                            <a href="watch.php?v=$episodes_list[$i]" class="main-container-25 ratio-4-3">
                                                <img src="$row2[poster]">
                                                <p>$row2[alt_title] - S$row2[season]O$row2[ep_number]</p>
                                            </a>
CONTENT;
                                }
                                $result2->free();
                            }
                        }
                    }
                    $result1->free();
                    echo "</div>";
                }
                ?>
                <h1>Losowo wybrane</h1>
                <div class="browse-section">
                    <?php
                    $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY RAND() LIMIT 4;";
                    if ($result = $con->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            echo <<< CONTENT
                                <a href="series.php?s=$row[id]" class="main-container-25 ratio-4-3">
                                    <img src="$row[poster]">
                                    <p>$row[alt_title]</p>
                                </a>
CONTENT;
                        }
                    }
                    $result->free();
                    ?>
                </div>
                <h1>Ostatnio dodane</h1>
                <div class="browse-section">
                    <?php
                    $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY `series`.`added_date` DESC LIMIT 4;";
                    if ($result = $con->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            echo <<< CONTENT
                                <a href="series.php?s=$row[id]" class="main-container-25 ratio-4-3">
                                    <img src="$row[poster]">
                                    <p>$row[alt_title]</p>
                                </a>
CONTENT;
                        }
                    }
                    $result->free();
                    ?>
                </div>
                <h1>Przygodowe</h1>
                <div class="browse-section">
                    <?php
                    $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `genre` LIKE '%przygodowe%' AND `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY RAND() LIMIT 4";
                    if ($result = $con->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            echo <<< CONTENT
                                <a href="series.php?s=$row[id]" class="main-container-25 ratio-4-3">
                                    <img src="$row[poster]">
                                    <p>$row[alt_title]</p>
                                </a>
CONTENT;
                        }
                    }
                    $result->free();
                    ?>
                </div>
                <h1>Akcja</h1>
                <div class="browse-section">
                    <?php
                    $query = "SELECT `series`.`id`, `series`.`title`, `series`.`alt_title`, `series`.`season`, `series`.`poster`, `series`.`desc`, `series`.`genre`, `series`.`added_date`, `series`.`brd-type`, `series`.`brd-start`, `series`.`brd-end`, `series`.`ep_count`, `series`.`isActive`, `series`.`tags` FROM `series` INNER JOIN `episodes` on `episodes`.`series_id` = `series`.`id` WHERE `genre` LIKE '%akcja%' AND `series`.`isActive` = 1 GROUP BY `series`.`id` HAVING COUNT(`episodes`.`id`) > 0 ORDER BY RAND() LIMIT 4";
                    if ($result = $con->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            echo <<< CONTENT
                                <a href="series.php?s=$row[id]" class="main-container-25 ratio-4-3">
                                    <img src="$row[poster]">
                                    <p>$row[alt_title]</p>
                                </a>
CONTENT;
                        }
                    }
                    $result->free();
                    ?>
                </div>
            </div>
        </div>
        <script src="scripts/dropdown.js"></script>
</body>

</html>