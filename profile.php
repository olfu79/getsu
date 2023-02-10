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
    <link rel="stylesheet" href="style/profile.css">
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
                    $query = "SELECT `id`, `alt_title` FROM `series` WHERE `isActive` = '1' ORDER BY `alt_title` ASC";
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
            <div class="main">
                <div class="user-card flex">
                    <?php
                    if (empty($_GET['u']) || $_GET['u'] == $_SESSION['id']) {
                        $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = '$_SESSION[id]'";
                        $result = $con->query($getUserData_query);
                        $res = $result->fetch_assoc();
                        $avatar = "<img class='pfp' src='resources/default.jpg' alt='User Avatar'>";
                        if ($res['avatar'] != "") {
                            $avatar = '<img class="pfp" src="data:image/jpeg;base64,' . base64_encode($res['avatar']) . '" alt="User Avatar">';
                        }
                        echo <<< USER_DATA
                        <div class="left flex flex-column">
                            $avatar
                            <span class="data-nowrap">Nazwa użytkownika: <b style="white-space: nowrap;">$res[username]</b></span>
                            <span class="data-nowrap">Email: <b>$res[email]</b></span>
                            <span class="data-nowrap">Ranga: <b>$res[role]</b></span>
                            <span class="data-nowrap">Data rejestracji: <b>$res[reg_date]</b></span>
                        </div>
                        <div class="right flex flex-column">
                            $res[description]
                        </div>
USER_DATA;
                    } else {
                        $getUserData_query = "SELECT * FROM `accounts` WHERE `id` = '$_GET[u]'";
                        $result = $con->query($getUserData_query);
                        if ($result->num_rows == 0) {
                            header('Location: scripts/error.php?e=nie ma takiego uzytkownika');
                            exit;
                        } else {
                            $res = $result->fetch_assoc();
                            $avatar = "<img class='pfp' src='resources/default.jpg' alt='User Avatar'>";
                            if ($res['avatar'] != "") {
                                $avatar = '<img class="pfp" src="data:image/jpeg;base64,' . base64_encode($res['avatar']) . '" alt="User Avatar">';
                            }
                            echo <<< USER_DATA
                            <div class="left flex flex-column">
                                $avatar
                                <span class="data-nowrap">Nazwa użytkownika: <b style="white-space: nowrap;">$res[username]</b></span>
                                <span class="data-nowrap">Email: <b>$res[email]</b></span>
                                <span class="data-nowrap">Ranga: <b>$res[role]</b></span>
                                <span class="data-nowrap">Data rejestracji: <b>$res[reg_date]</b></span>
                            </div>
                            <div class="right flex flex-column">
                                $res[description]
                            </div>
    USER_DATA;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts/dropdown.js"></script>
</body>

</html>