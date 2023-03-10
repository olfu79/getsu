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
    <link rel="stylesheet" href="style/manage-content.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <?php if ($_SESSION['role'] == "admin") {
                    echo <<< ADMIN_SECTION
                    <hr>
                    <a href="add_item.php">
                        <span class="mdi mdi-plus"></span>Dodaj
                    </a>
                    <a href="reports.php">
                        <span class="mdi mdi-flag"></span>Zgłoszenia
                    </a>
                    <a class="active" href="manage-content.php">
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
                    <a href="index.php" class="nav-top-notifications"><span class="mdi mdi-bell"></span></a>
                    <a href="profile.php" class="nav-top-profile"><span class="mdi mdi-account-circle"></a>
                </div>
            </div>
            <div class="main flex-column">
                <h1>Zarządzaj zawartością</h1>
                <div class="browse-section">
                    <?php
                    $seriesData_query = "SELECT `id`, `alt_title`, `isActive` FROM `series` ORDER BY `alt_title`";
                    $result = $con->query($seriesData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='series-header'>
                                        <th>ID</th>
                                        <th>Nazwa serii</th>
                                        <th class='actions-header'>Akcje</th>
                                    </tr>";
                        while ($seriesRow = $result->fetch_assoc()) {
                            $seriesId = $seriesRow["id"];
                            $output .= "<tr class='series-row' data-series-id='$seriesId'>
                                            <td class='series_id'>{$seriesRow["id"]}</td>
                                            <td class='series_title'>{$seriesRow["alt_title"]}</td>
                                            <td class='series_actions'>
                                                <div class='actions flex v-mid'>";
                            if ($seriesRow['isActive'] == 1) {

                                $output .= "<a href='series.php?s={$seriesId}'><span class='mdi mdi-open-in-new'></span></a>
                                            <a href='scripts/manage-content-actions.php?s={$seriesId}&action=hide'><span class='mdi mdi-eye-outline'></span></a>";
                            } else if ($seriesRow['isActive'] == 0) {
                                $output .= "<a href='scripts/manage-content-actions.php?s={$seriesId}&action=show'><span class='mdi mdi-eye-off-outline'></span></a>";
                            }
                            $output .= "<a href='scripts/manage-content-actions.php?s={$seriesId}&action=edit'><span class='mdi mdi-text-box-edit-outline'></span></a>
                                        <a href='scripts/manage-content-actions.php?s={$seriesId}&action=delete'><span class='mdi mdi-trash-can-outline'></span></a>
                                    </div>
                                </td>
                            </tr>";
                            $episodesData_query = "SELECT `id`, `title`, `ep_number`, `isActive` FROM `episodes` WHERE `series_id` = '$seriesId' ORDER BY `ep_number` ASC";
                            $episodesResult = $con->query($episodesData_query);
                            if ($episodesResult->num_rows > 0) {
                                $output .= "<tr class='episodes-container'>
                                                <td colspan='3'>
                                                    <table>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Odcinek</th>
                                                            <th>Tytuł</th>
                                                            <th class='actions-header'>Akcje</th>
                                                        </tr>";
                                while ($episodeRow = $episodesResult->fetch_assoc()) {
                                    $episodeId = $episodeRow["id"];
                                    $output .= "<tr class='episodes-row' data-episode-id='$episodeId'>
                                                    <td class='episode_id'>{$episodeId}</td>
                                                    <td class='episode_number'>{$episodeRow["ep_number"]}</td>
                                                    <td class='episode_title'>{$episodeRow["title"]}</td>
                                                    <td class='episode_actions'>
                                                        <div class='actions flex v-mid'>";
                                    if ($episodeRow['isActive'] == 1) {
                                        $output .= "<a href='watch.php?v={$episodeId}'><span class='mdi mdi-open-in-new'></span></a>
                                                    <a href='scripts/manage-content-actions.php?e={$episodeId}&action=hide'><span class='mdi mdi-eye-outline'></span></a>";
                                    } else if ($episodeRow['isActive'] == 0) {
                                        $output .= "<a href='scripts/manage-content-actions.php?e={$episodeId}&action=show'><span class='mdi mdi-eye-off-outline'></span></a>";
                                    }
                                    $output .= "<a href='scripts/manage-content-actions.php?e={$episodeId}&action=edit'><span class='mdi mdi-text-box-edit-outline'></span></a>
                                                <a href='scripts/manage-content-actions.php?e={$episodeId}&action=delete'><span class='mdi mdi-trash-can-outline'></span></a>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                                $output .= "</table></td></tr>";
                            } else {
                                $output .= "<tr class='episodes-container'><td colspan='3'>Ta seria nie ma żadnych odcinków</td></tr>";
                            }
                        }
                        $output .= "</table>";
                    } else {
                        $output = "Nie znaleziono żadnych serii.";
                    }
                    echo $output;
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.series-row').click(function() {
                $(this).next('.episodes-container').toggle();
            });
        });
    </script>
    <script src="scripts/dropdown.js"></script>
</body>

</html>