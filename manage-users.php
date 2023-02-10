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
    <link rel="stylesheet" href="style/manage-users.css">
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
                    <a class="active" href="manage-users.php">
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
                <h1>Zarządzaj użytkownikami</h1>
                <div class="browse-section">
                    <?php
                    $usersData_query = "SELECT `id`, `username`, `email`, `role` FROM `accounts` ORDER BY `id`";
                    $result = $con->query($usersData_query);

                    if ($result->num_rows > 0) {
                        $output = "<table>
                                    <tr class='users-header'>
                                        <th>ID</th>
                                        <th>Nazwa użytkownika</th>
                                        <th>Email</th>
                                        <th>Rola</th>
                                        <th class='actions-header'>Akcje</th>
                                    </tr>";
                        while ($usersRow = $result->fetch_assoc()) {
                            $userId = $usersRow["id"];
                            $output .= "<tr class='users-row' data-users-id='$userId'>
                                            <td class='users_id'>{$usersRow["id"]}</td>
                                            <td class='users_username'>{$usersRow["username"]}</td>
                                            <td class='users_email'>{$usersRow["email"]}</td>
                                            <td class='users_role'>{$usersRow["role"]}</td>
                                            <td class='users_actions'>
                                                <div class='actions flex v-mid'>
                                                    <a href='scripts/manage-users-actions.php?u={$userId}&action=reset'><span class='mdi mdi-lock-reset'></span></a>
                                                    <a href='scripts/manage-users-actions.php?u={$userId}&action=edit'><span class='mdi mdi-text-box-edit-outline'></span></a>
                                                    <a href='scripts/manage-users-actions.php?u={$userId}&action=block'><span class='mdi mdi-account-cancel'></span></a>
                                                    <a href='scripts/manage-users-actions.php?u={$userId}&action=delete'><span class='mdi mdi-trash-can-outline'></span></a>
                                                </div>
                                            </td>
                                        </tr>";
                        }
                        $output .= "</table>";
                    } else {
                        $output = "Nie znaleziono żadnych użytkowników.";
                    }
                    echo $output;
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    </script>
    <script src="scripts/dropdown.js"></script>
</body>

</html>