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
                <h1>Zarządzaj zawartością</h1>
                <div class="browse-section">
                    <?php
                    $seriesData_query = "SELECT `id`, `alt_title` FROM `series` ORDER BY `alt_title`";
                    $result = $con->query($seriesData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='series-header'>
                                        <th>ID</th>
                                        <th>Nazwa serii</th>
                                        <th class='actions-header'>Akcje</th>
                                    </tr>";
                        while ($row = $result->fetch_assoc()) {
                            $seriesId = $row["id"];
                            $output .= "<tr class='series-row'>
                                            <td class='series_id'>{$row["id"]}</td>
                                            <td class='series_title'>{$row["alt_title"]}</td>
                                            <td class='series_actions'>
                                                <div class='actions flex v-mid'>
                                                    <span class='mdi mdi-eye-outline'></span>
                                                    <span class='mdi mdi-text-box-edit-outline'></span>
                                                    <span class='mdi mdi-trash-can-outline'></span>
                                                </div>
                                            </td>
                                        </tr>";
                            $spisodesData_query = "SELECT `id`, `title`, `ep_number` FROM `episodes` WHERE `series_id` = '$seriesId' ORDER BY `ep_number` ASC";
                            $episodesResult = $con->query($spisodesData_query);
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
                                    $output .= "<tr class='episodes-row'>
                                                    <td class='episode_id'>{$episodeRow["id"]}</td>
                                                    <td class='episode_number'>{$episodeRow["ep_number"]}</td>
                                                    <td class='episode_title'>{$episodeRow["title"]}</td>
                                                    <td class='episode_actions'>
                                                        <div class='actions flex v-mid'>
                                                            <span class='mdi mdi-eye-outline'></span>
                                                            <span class='mdi mdi-text-box-edit-outline'></span>
                                                            <span class='mdi mdi-trash-can-outline'></span>
                                                        </div>
                                                    </td>
                                                </tr>";
                                }
                                $output .= "</table></td></tr>";
                            } else {
                                $output .= "<tr class='episodes-row'><td colspan='2'>No episodes found</td></tr>";
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