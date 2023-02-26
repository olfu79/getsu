<?php
include 'scripts/isloggedin.php';
include 'scripts/db_con.php';
include 'scripts/seriesAuth.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/series.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="scripts/ptw-handler.js"></script>
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
                    <span class="mdi mdi-compass"></span>Strona Główna
                </a>
                <a href="javascript:void(0);" class="dropdown active">
                    <span class="mdi mdi-format-list-bulleted-square"></span>Lista anime
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
            <div class="main flex-row">
                <div class="series-left-pane">
                    <div class="slideshow-container">
                        <?php
                        $posters_query = "SELECT `episodes`.`poster` FROM `episodes` WHERE `episodes`.`series_id` = '$series_id' ORDER BY RAND() LIMIT 5";
                        $result = $con->query($posters_query);
                        echo "<div class='slide'><img src='$poster'></div>";
                        $imgCount = 1;
                        while ($res = $result->fetch_assoc()) {
                            echo "<div class='slide'><img src='$res[poster]'></div>";
                            $imgCount++;
                        }
                        $result->free();
                        ?>

                        <a class="prev" onclick="plusSlides(-1)">❮</a>
                        <a class="next" onclick="plusSlides(1)">❯</a>

                        <div class="dot-wrapper">
                            <?php
                            for ($i = 0; $i < $imgCount; $i++) {
                                echo "<span class='dot'></span>";
                            }
                            ?>
                        </div>

                    </div>
                    <div class="series-data">
                        <?php
                        include 'scripts/ptw-status.php';
                        echo <<< SERIES_DATA
                        <h2>$alt_title</h2>
                        $title<br><br>
                        <b>Sezon:</b> $season<br>
                        <b>Odcinków:</b> $epCount<br>
                        <b>Gatunek:</b> $genre<br>
                        <b>Data rozpoczęcia:</b> $brdStart<br>
                        <b>Data zakończenia:</b> $brdEnd<br><br>
                        <h4>Opis:</h4>
                        <p>$desc</p>
SERIES_DATA;
                        ?>
                    </div>
                </div>
                <div class="series-right-pane">
                    <h1 class="ep-list-header">Lista odcinków:</h1>
                    <div class="series-ep-list">
                        <ul>
                            <?php
                            $episodes_query = "SELECT `id`, `title`, `ep_number` FROM `episodes` 
                            WHERE `series_id` = '$series_id' 
                            AND `isActive` = 1 
                            ORDER BY `ep_number` ASC";
                            $result = $con->query($episodes_query);
                            while ($res = $result->fetch_assoc()) {
                                echo "<a href='watch.php?v=$res[id]'><li><span class='epNum'>$res[ep_number].</span> $res[title]</li></a><hr>";
                            }
                            $result->free();
                            ?>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("<?php echo $series_id; ?>").style.backgroundColor = "#161616";

        $('.series-ep-list').height($('.series-data').innerHeight() + $('.slideshow-container').innerHeight() - $('.ep-list-header').innerHeight());
    </script>
    <script src="scripts/slideshow.js"></script>
    <script src="scripts/dropdown.js"></script>
</body>

</html>