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
    <link rel="stylesheet" href="style/add_item.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="scripts/add_item-adjustSize.js"></script>
    <script src="scripts/add_item-regenerateId.js"></script>
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
            <div class="main flex-row">
                <div class="add-series-button active">
                    <h3>Dodaj serię</h3>
                </div>
                <div class="add-episode-button">
                    <h3>Dodaj odcinek</h3>
                </div>
                <div class="add-series-form active">
                    <form id="form-addSeries" method="POST" action="scripts/add_series.php">
                        <div class="form-group">
                            <div class="form-item id">
                                <label>ID</label>
                                <div class="flex flex-row v-mid">
                                    <input id="form-seriesId" type="text" name="id" min="100000000" max="999999999" readonly="readonly" required>
                                    <span class="mdi mdi-restart regenerate-seriesID-button"></span>
                                </div>
                            </div>
                            <div class="form-item altname">
                                <label>Zwyczajowa nazwa</label>
                                <input type="text" name="altname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item fullname">
                                <label>Pełna nazwa</label>
                                <input type="text" name="fullname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item season">
                                <label>Sezon</label>
                                <input type="number" min="0" value="1" name="season" required>
                            </div>
                            <div class="form-item epcount">
                                <label>Liczba odcinków</label>
                                <input type="number" min="0" value="12" name="epcount" required>
                            </div>
                            <div class="form-item brdtype">
                                <label>Rodzaj</label>
                                <select name="brdtype" required>
                                    <option selected>TV</option>
                                    <option>OVA</option>
                                    <option>Odcinek Specjalny</option>
                                    <option>Film</option>
                                </select>
                            </div>
                            <div class="form-item brdstart">
                                <label>Rozpoczęto</label>
                                <input type="date" name="brdstart" required>
                            </div>
                            <div class="form-item brdend">
                                <label>Zakończono</label>
                                <input type="date" name="brdend" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item genre">
                                <label>Gatunek</label>
                                <select multiple multiselect-search="true" name="genre[]">
                                    <option>Akcja</option>
                                    <option>Dramat</option>
                                    <option>Ecchi</option>
                                    <option>Eksperymentalne</option>
                                    <option>Fantasy</option>
                                    <option>Harem</option>
                                    <option>Hentai</option>
                                    <option>Historyczne</option>
                                    <option>Horror</option>
                                    <option>Komedia</option>
                                    <option>Kryminalne</option>
                                    <option>Magia</option>
                                    <option>Mecha</option>
                                    <option>Muzyczne</option>
                                    <option>Nadprzyrodzone</option>
                                    <option>Okruchy życia</option>
                                    <option>Przygodowe</option>
                                    <option>Psychologiczne</option>
                                    <option>Romans</option>
                                    <option>Sci-Fi</option>
                                    <option>Sportowe</option>
                                    <option>Steampunk</option>
                                    <option>Szkolne</option>
                                    <option>Sztuki walki</option>
                                    <option>Tajemnica</option>
                                    <option>Thriller</option>
                                    <option>Wojskowe</option>
                                    <option>Yuri</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item desc">
                                <label>Opis</label>
                                <textarea name="desc" rows="4" maxlength="1024" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item poster">
                                <label>Miniaturka</label>
                                <input type="text" name="poster" required>
                            </div>
                            <div class="form-item tags">
                                <label>Tagi</label>
                                <input type="text" name="tags">
                            </div>
                        </div>
                        <div class="form-group form-controls">
                            <div class="form-item submit">
                                <input type="submit" value="Dodaj serię">
                            </div>
                            <div class="form-item reset">
                                <input type="reset" value="Resetuj">
                            </div>
                        </div>
                    </form>
                    <script src="scripts/add_item-validateSeries.js"></script>
                </div>
                <div class="add-episode-form">
                    <form id="form-addEpisode" method="POST" action="scripts/add_episode.php">
                        <div class="form-group">
                            <div class="form-item id">
                                <label>ID</label>
                                <div class="flex flex-row v-mid">
                                    <input id="form-episodeId" type="text" name="id" min="100000000" max="999999999" readonly="readonly" required>
                                    <span class="mdi mdi-restart regenerate-episodeID-button"></span>
                                </div>
                            </div>
                            <div class="form-item series">
                                <label>Seria</label>
                                <select name="series" required>
                                    <option disabled selected value="">Wybierz serię</option>
                                    <?php
                                    $query = "SELECT `id`, `alt_title`, `season` FROM `series` ORDER BY `alt_title` ASC";
                                    $result = $con->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='$row[id]'>$row[alt_title] [S{$row['season']}]</option>";
                                    }
                                    $result->free();
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item ep-title">
                                <label>Tytuł odcinka</label>
                                <input type="text" name="title" required>
                            </div>
                            <div class="form-item ep-number">
                                <label>Numer odcinka</label>
                                <input type="number" name="number" min="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item ep-url">
                                <label>URL Filmu</label>
                                <input type="url" name="url" required>
                            </div>
                            <div class="form-item ep-poster">
                                <label>URL Miniaturki</label>
                                <input type="url" name="poster" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item desc">
                                <label>Opis</label>
                                <textarea name="desc" rows="4" maxlength="2048"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-item intro">
                                <label>Koniec intro</label>
                                <div class="flex flex-row v-mid">
                                    <input id="intro-min" type="number" name="minutes" min="0">
                                    &nbsp;<h1>:</h1>&nbsp;
                                    <input type="number" name="seconds" min="0" max="59">
                                </div>
                            </div>
                            <div class="form-item isVisible">
                                <label>Publiczny</label>
                                <input type="checkbox" name="visible" checked>
                            </div>
                        </div>
                        <div class="form-group form-controls">
                            <div class="form-item submit">
                                <input type="submit" value="Dodaj odcinek">
                            </div>
                            <div class="form-item reset">
                                <input type="reset" value="Resetuj">
                            </div>
                        </div>
                    </form>
                    <script src="scripts/add_item-validateEpisode.js"></script>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.add-series-button').click(function() {
                if (!$(this).hasClass('active')) {
                    $('.add-series-form').toggleClass('active').css('display', 'block');
                    $('.add-episode-form').removeClass('active').css('display', 'none');
                    $('.add-series-button').toggleClass('active');
                    $('.add-episode-button').removeClass('active');
                    setSelectSize();
                }
            });

            $('.add-episode-button').click(function() {
                if (!$(this).hasClass('active')) {
                    $('.add-series-form').removeClass('active').css('display', 'none');
                    $('.add-episode-form').toggleClass('active').css('display', 'block');
                    $('.add-series-button').removeClass('active');
                    $('.add-episode-button').toggleClass('active');
                    setSelectSize()
                }
            });
        });
    </script>
    <script src="scripts/multiselect-dropdown.js"></script>
    <script src="scripts/dropdown.js"></script>
</body>

</html>