<?php
include 'scripts/isloggedin.php';
include 'scripts/isadmin.php';
include 'scripts/db_con.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/edit-content.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="scripts/add_item-adjustSize.js"></script>
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
                    $query = "SELECT `id`, `alt_title` FROM `series` ORDER BY `alt_title` ASC";
                    $result = $con->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<a href='series.php?s=$row[id]'>$row[alt_title]</a>";
                    }
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
                    <a onclick="" class="nav-top-filter"><span class="mdi mdi-filter-variant"></span></a>
                </div>
                <div class="nav-top-right">
                    <a href="index.php"><span class="mdi mdi-bell"></span></a>
                    <a href="profile.php"><span class="mdi mdi-account-circle"></a>
                </div>
            </div>
            <div class="main flex-row">
                <?php
                if (!empty($_GET['s'])) {
                    $seriesData_query = "SELECT * FROM `series` WHERE `id` = '$_GET[s]'";
                    $result = $con->query($seriesData_query);
                    if ($result->num_rows == 0) {
                        header('Location: error.php?e=nie ma takiej serii');
                        exit;
                    } else {
                        $res = $result->fetch_assoc();
                        $series_id = $res['id'];
                        $title = $res['title'];
                        $alt_title = $res['alt_title'];
                        $season = $res['season'];
                        $poster = $res['poster'];
                        $desc = $res['desc'];
                        $genre = $res['genre'];
                        $brdType = $res['brd-type'];
                        $brdStart = $res['brd-start'];
                        $brdEnd = $res['brd-end'];
                        $epCount = $res['ep_count'];
                        $tags = $res['tags'];

                        $optionTV = "<option value='TV'>TV</option>";
                        $optionOVA = "<option value='OVA'>OVA</option>";
                        $optionSpecialEpisode = "<option value='Odcinek Specjalny'>Odcinek Specjalny</option>";
                        $optionFilm = "<option value='Film'>Film</option>";

                        if ($brdType == 'TV') {
                            $optionTV = "<option value='TV' selected>TV</option>";
                        } elseif ($brdType == 'OVA') {
                            $optionOVA = "<option value='OVA' selected>OVA</option>";
                        } elseif ($brdType == 'Odcinek Specjalny') {
                            $optionSpecialEpisode = "<option value='Odcinek Specjalny' selected>Odcinek Specjalny</option>";
                        } elseif ($brdType == 'Film') {
                            $optionFilm = "<option value='Film' selected>Film</option>";
                        }
                        $genre =  rtrim($genre, '; ');
                        $genre = explode("; ", $genre);
                        $options = array("Akcja", "Dramat", "Ecchi", "Eksperymentalne", "Fantasy", "Harem", "Hentai", "Historyczne", "Horror", "Komedia", "Kryminalne", "Magia", "Mecha", "Muzyczne", "Nadprzyrodzone", "Okruchy życia", "Przygodowe", "Psychologiczne", "Romans", "Sci-Fi", "Sportowe", "Steampunk", "Szkolne", "Sztuki walki", "Tajemnica", "Thriller", "Wojskowe", "Yuri");
                        $genreOptions = "";
                        foreach ($options as $option) {
                            $genreOptions .= ('<option ' . (in_array($option, $genre) ? 'selected' : '') . '>' . $option . '</option>');
                        }
                        echo <<< SERIES_EDIT_FORM
                        <div class="edit-series-form">
                        <h1>Edycja Serii</h1>
                        <form id="form-editSeries" method="POST" action="scripts/update_series.php">
                            <div class="form-group">
                                <div class="form-item id">
                                    <label>ID</label>
                                    <div class="flex flex-row v-mid">
                                        <input id="form-seriesId" type="text" name="id" min="100000000" max="999999999" value='$series_id' readonly required>
                                    </div>
                                </div>
                                <div class="form-item altname">
                                    <label>Zwyczajowa nazwa</label>
                                    <input type="text" name="altname" value='$alt_title' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item fullname">
                                    <label>Pełna nazwa</label>
                                    <input type="text" name="fullname" value='$title' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item season">
                                    <label>Sezon</label>
                                    <input type="number" min="0" value="1" name="season" value='$season' required>
                                </div>
                                <div class="form-item epcount">
                                    <label>Liczba odcinków</label>
                                    <input type="number" min="0" name="epcount" value='$epCount' required>
                                </div>
                                <div class="form-item brdtype">
                                    <label>Rodzaj</label>
                                    <select name="brdtype">
                                        $optionTV
                                        $optionOVA
                                        $optionSpecialEpisode
                                        $optionFilm
                                    </select>
                                </div>
                                <div class="form-item brdstart">
                                    <label>Rozpoczęto</label>
                                    <input type="date" name="brdstart" value='$brdStart' required>
                                </div>
                                <div class="form-item brdend">
                                    <label>Zakończono</label>
                                    <input type="date" name="brdend" value='$brdEnd' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item genre">
                                    <label>Gatunek</label>
                                    <select multiple multiselect-search="true" name="genre[]">
                                    $genreOptions
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item desc">
                                    <label>Opis</label>
                                    <textarea name="desc" rows="4" maxlength="1024" required>$desc</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item poster">
                                    <label>Miniaturka</label>
                                    <input type="text" name="poster" value='$poster' required>
                                </div>
                                <div class="form-item tags">
                                    <label>Tagi</label>
                                    <input type="text" name="tags" value='$tags'>
                                </div>
                            </div>
                            <div class="form-group form-controls">
                                <div class="form-item submit">
                                    <input type="submit" value="Zapisz informacje">
                                </div>
                                <div class="form-item reset">
                                    <input type="reset" value="Anuluj">
                                </div>
                            </div>
                        </form>
                        <script src="scripts/add_item-validateSeries.js"></script>
                    </div>
SERIES_EDIT_FORM;
                    }
                } else if (!empty($_GET['e'])) {
                    $seriesData_query = "SELECT * FROM `episodes` WHERE `id` = '$_GET[e]'";
                    $result = $con->query($seriesData_query);
                    if ($result->num_rows == 0) {
                        header('Location: error.php?e=nie ma takiego odcinka');
                        exit;
                    } else {
                        $res = $result->fetch_assoc();
                        $id = $res['id'];
                        $series_id = $res['series_id'];
                        $url = $res['url'];
                        $poster = $res['poster'];
                        $title = $res['title'];
                        $ep_number = $res['ep_number'];
                        $isActive = $res['isActive'];
                        $desc = $res['desc'];
                        $added_date = $res['added_date'];
                        $likes = $res['likes'];
                        $intro_end = $res['intro_end'];

                        $minutes = floor($intro_end / 60);
                        $seconds = $intro_end % 60;
                        $isChecked = $isActive = 1 ? "checked" : "";

                        $query = "SELECT `id`, `alt_title`, `season` FROM `series` ORDER BY `alt_title` ASC";
                        $result = $con->query($query);
                        $options = "";
                        while ($row = $result->fetch_assoc()) {
                            if ($series_id == $row['id']) {
                                $options .= "<option value='$row[id]' selected>$row[alt_title] [S{$row['season']}]</option>";
                            } else {
                                $options .= "<option value='$row[id]'>$row[alt_title] [S{$row['season']}]</option>";
                            }
                        }

                        echo <<< EPISODE_EDIT_FORM
                        <div class="edit-episode-form">
                        <h1>Edycja Odcinka</h1>
                        <form id="form-editEpisode" method="POST" action="scripts/update_episode.php">
                            <div class="form-group">
                                <div class="form-item id">
                                    <label>ID</label>
                                    <div class="flex flex-row v-mid">
                                        <input id="form-episodeId" type="text" name="id" min="100000000" max="999999999" readonly value='$id' required>
                                    </div>
                                </div>
                                <div class="form-item series">
                                    <label>Seria</label>
                                    <select name="series" required>
                                        $options
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item ep-title">
                                    <label>Tytuł odcinka</label>
                                    <input type="text" name="title" value='$title' required>
                                </div>
                                <div class="form-item ep-number">
                                    <label>Numer odcinka</label>
                                    <input type="number" name="number" min="0" value='$ep_number' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item ep-url">
                                    <label>URL Filmu</label>
                                    <input type="url" name="url" value='$url' required>
                                </div>
                                <div class="form-item ep-poster">
                                    <label>URL Miniaturki</label>
                                    <input type="url" name="poster" value='$poster' required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item desc">
                                    <label>Opis</label>
                                    <textarea name="desc" rows="4" maxlength="2048">$desc</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-item intro">
                                    <label>Koniec intro</label>
                                    <div class="flex flex-row v-mid">
                                        <input id="intro-min" type="number" name="minutes" min="0" value='$minutes'>
                                        &nbsp;<h1>:</h1>&nbsp;
                                        <input type="number" name="seconds" min="0" max="59" value='$seconds'>
                                    </div>
                                </div>
                                <div class="form-item isVisible">
                                    <label>Publiczny</label>
                                    <input type="checkbox" name="visible" $isChecked>
                                </div>
                            </div>
                            <div class="form-group form-controls">
                                <div class="form-item submit">
                                    <input type="submit" value="Zapisz">
                                </div>
                                <div class="form-item reset">
                                    <input type="reset" value="Anuluj">
                                </div>
                            </div>
                        </form>
                        <script src="scripts/add_item-validateEpisode.js"></script>
                    </div>
EPISODE_EDIT_FORM;
                    }
                } else {
                    header('Location: error.php?e=nie podano elementu do edycji');
                    exit;
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            setSelectSize();
        });
    </script>
    <script src="scripts/dropdown.js"></script>
</body>

</html>