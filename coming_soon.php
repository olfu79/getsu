<?php
include 'scripts/isloggedin.php';
include 'scripts/db_con.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style/coming-soon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
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
                    $result->free();
                    ?>
                </div>
                <a href="watchlist.php">
                    <span class="mdi mdi-playlist-play"></span>Do obejrzenia
                </a>
                <a class="active" href="coming_soon.php">
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
                <h1>Nadchodzące</h1>
                DODAĆ TU KALENDARZ Z ITEMAMI PO KLIKNIECIU PRZECHODZI NA SERIE. NAJPIERW TRZEBA DODAC SERIE A POTEM PRZY DODAWANIU DO KALENDARZA MOZNA WYBRAC TYLKO TE KTORE MAJA RELEASE DATE NOWSZY NIZ CURRENT_DATE
                <div class="comingsoon-section">
                    <?php
                    $query1 = "SELECT * FROM `series` WHERE `brd-start` > CURRENT_DATE() ORDER BY `brd-start` ASC";
                    if ($result1 = $con->query($query1)) {
                        while ($row1 = $result1->fetch_assoc()) {
                            echo <<< CONTENT
                                <a href="series.php?s=$row1[id]" class="main-container-25 ratio-4-3">
                                    <img src="$row1[poster]">
                                    <p>$row1[alt_title]</p>
                                    <p>Premiera: {$row1["brd-start"]}</p>
                                </a>
CONTENT;
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