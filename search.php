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
    <link rel="stylesheet" href="style/search.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
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
            <div class="main flex-column">
                <h1>Wyszukiwanie "<span style="color: #A61C1C;"><?php echo $_GET['search'] ?></span>"</h1><br>

                <?php
                // get the search term from GET request
                $search_term = $con->real_escape_string($_GET['search']);

                // Search for series based on LEVENSHTEIN_RATIO
                $series_query = "SELECT id, title, alt_title, poster, isActive, LEVENSHTEIN_RATIO(title, '$search_term') as title_similarity, 
                  LEVENSHTEIN_RATIO(alt_title, '$search_term') as alt_title_similarity,
                  LEVENSHTEIN_RATIO(tags, '$search_term') as tags_similarity
                  FROM series 
                  WHERE isActive = 1 AND
                  ((title LIKE '%$search_term%' OR alt_title LIKE '%$search_term%' OR tags LIKE '%$search_term%') 
                  OR (LEVENSHTEIN_RATIO(title, '$search_term') >= 0.7 
                      OR LEVENSHTEIN_RATIO(alt_title, '$search_term') >= 0.7 
                      OR LEVENSHTEIN_RATIO(tags, '$search_term') >= 0.7))
                  ORDER BY 
                  (CASE
                    WHEN title LIKE '$search_term%' THEN 1
                    WHEN alt_title LIKE '$search_term%' THEN 2
                    ELSE 3
                  END),
                  title_similarity DESC, alt_title_similarity DESC, tags_similarity DESC";

                // execute the series query
                $series_result = $con->query($series_query);
                if ($series_result->num_rows > 0) {
                    echo "<h2>Serie:</h2>";
                    while ($series_row = $series_result->fetch_assoc()) {
                        // output the series details
                        echo "
                        <a href='series.php?s=$series_row[id]'>
                            <div class='search-item flex flex-row'>
                                <img src='$series_row[poster]'>
                                <div class='item-data'>
                                    <h4>$series_row[title]</h4>
                                    <p>$series_row[alt_title]</p>
                                </div>
                            </div>
                        </a><br>";
                    }
                }
                // loop through the series results


                // Search for episodes based on LEVENSHTEIN_RATIO
                $episode_query = "SELECT episodes.id, episodes.title, episodes.poster, episodes.ep_number, episodes.isActive, series.season, episodes.poster, series.alt_title, LEVENSHTEIN_RATIO(episodes.title, '$search_term') as title_similarity
                    FROM episodes
                    INNER JOIN series ON series.id = episodes.series_id
                    WHERE episodes.isActive = 1 AND (episodes.title LIKE '%$search_term%' OR series.alt_title LIKE '%$search_term%' OR LEVENSHTEIN_RATIO(episodes.title, '$search_term') >= 0.7 OR LEVENSHTEIN_RATIO(series.alt_title, '$search_term') >= 0.7)
                    ORDER BY episodes.ep_number";
                // execute the episode query
                $episode_result = $con->query($episode_query);

                // loop through the episode results and output them
                if ($episode_result->num_rows > 0) {
                    echo "<h2>Odcinki:</h2>";
                    while ($episode_row = $episode_result->fetch_assoc()) {
                        echo "
                        <a href='watch.php?v={$episode_row["id"]}'>
                            <div class='search-item flex flex-row'>
                                <img src='$episode_row[poster]'>
                                <div class='item-data'>
                                    <h4>$episode_row[alt_title] S$episode_row[season] O$episode_row[ep_number]</h4>
                                    <p>$episode_row[title]</p>
                                </div>
                            </div>
                        </a><br>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script src="scripts/dropdown.js"></script>
</body>

</html>