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
    <link rel="stylesheet" href="style/coming-soon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <link rel="icon" type="image/png" href="logo/favicon.png" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/calendar.js"></script>
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
                <?php if ($_SESSION['role'] == "admin") {
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
                <a href="contact.php">
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
            <div class="main flex-column">
                <h1>Nadchodzące</h1>
                <div class="comingsoon-section">
                    <div id="calendar-container">
                        <div id="calendar-controls">
                            <div id="month-back"><span class="mdi mdi-menu-left-outline"></span></div>
                            <div id="month"></div>
                            <div id="month-forward"><span class="mdi mdi-menu-right-outline"></span></div>
                        </div>
                        <div id="calendar-header">
                            <div class="day-of-week"><span>Poniedziałek</span></div>
                            <div class="day-of-week"><span>Wtorek</span></div>
                            <div class="day-of-week"><span>Środa</span></div>
                            <div class="day-of-week"><span>Czwartek</span></div>
                            <div class="day-of-week"><span>Piątek</span></div>
                            <div class="day-of-week"><span>Sobota</span></div>
                            <div class="day-of-week"><span>Niedziela</span></div>
                        </div>
                        <div id="calendar"></div>
                    </div>
                </div>
                <div class="comingsoon-section comingsoon-list flex-column">
                    <?php
                    $query1 = "SELECT * FROM `series` WHERE `brd-start` > CURRENT_DATE() ORDER BY `brd-start` ASC";
                    if ($result1 = $con->query($query1)) {
                        $previous_month = null;
                        $months = array(
                            1 => 'Styczeń',
                            2 => 'Luty',
                            3 => 'Marzec',
                            4 => 'Kwiecień',
                            5 => 'Maj',
                            6 => 'Czerwiec',
                            7 => 'Lipiec',
                            8 => 'Sierpień',
                            9 => 'Wrzesień',
                            10 => 'Październik',
                            11 => 'Listopad',
                            12 => 'Grudzień'
                        );

                        while ($row1 = $result1->fetch_assoc()) {
                            $current_month_num = date('n', strtotime($row1['brd-start']));
                            $current_month = $months[$current_month_num];
                            if ($current_month !== $previous_month) {
                                echo "<h3 class='month-name'>$current_month:</h3>";
                                $previous_month = $current_month;
                            }
                            echo "<a href='series.php?s=$row1[id]'>
                                <div class='upcoming-item flex flex-row'>
                                    <img src='$row1[poster]'>
                                    <div class='upcoming-data'>
                                        <h4>$row1[title]</h4>
                                        <p>Orginalny tytuł: <i>$row1[alt_title]</i></p>
                                        <p>Sezon: $row1[season]</p>
                                        <p>Odcinków: $row1[ep_count]</p>
                                    </div>
                                </div>
                            </a><br>";
                        }
                    }
                    $result1->free();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts/dropdown.js"></script>
</body>

</html>