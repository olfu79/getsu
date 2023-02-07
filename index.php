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
    <link rel="stylesheet" href="style/browse-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <title>Onifu.pl</title>
</head>

<body>
    <div class="wrapper">
        <div class="left-pane">
            <div class="logo">
                <a href="index.php">
                    <img src="logo/onifu-white.png" alt="logo" draggable="false" />
                </a>
            </div>
            <hr>
            <div class="navbar-left">
                <a class="active" href="index.php">
                    <span class="mdi mdi-compass"></span>Strona Główna
                </a>
                <a href="javascript:void(0);" class="dropdown">
                    <span class="mdi mdi-format-list-bulleted-square"></span>Lista anime
                </a>
                <div class="dropdown-container">
                    <?php
                    $query = "SELECT `id`, `alt_title` FROM `series` ORDER BY `alt_title` ASC";
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
                <?php
                $query1 = "SELECT * FROM `history` INNER JOIN `accounts` ON `accounts`.`id` = `history`.`id` WHERE `accounts`.`id` = '$_SESSION[id]'";
                $result1 = $con->query($query1);
                if ($result1->num_rows > 0) {
                    echo "<h1>Ostatnio oglądane</h1><div class='browse-section'>";
                    while ($row1 = $result1->fetch_assoc()) {
                        $episodes_list = explode(";", $row1['last_watched']);
                        $history_count = count($episodes_list) > 4 ? 4 : count($episodes_list);
                        for ($i = 0; $i < $history_count; $i++) {
                            $query2 = "SELECT `series`.`alt_title`, `episodes`.`poster`, `series`.`season`, `episodes`.`ep_number` FROM `episodes` INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id` WHERE `episodes`.`id`=$episodes_list[$i]";
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
                    $query = "SELECT * FROM `series` ORDER BY RAND();";
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
                    $query = "SELECT * FROM `series` ORDER BY `added_date`";
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
                    $query = "SELECT * FROM `series` WHERE `genre` LIKE '%przygodowe%' ORDER BY RAND();";
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
    </div>
    <script src="scripts/dropdown.js"></script>
</body>

</html>