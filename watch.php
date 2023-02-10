<?php
include 'scripts/isloggedin.php';
include 'scripts/db_con.php';
include 'scripts/episodeAuth.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://vjs.zencdn.net/7.21.1/video-js.css">
    <link rel="stylesheet" href="style/player-style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css">
    <title>Getsu</title>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="scripts/like-handler.js"></script>
    <script src="scripts/download.js"></script>
    <script src="scripts/report-comment.js"></script>

    <!-- Videojs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/video.js@7.6.6/dist/video-js.css">
    <script src="https://cdn.jsdelivr.net/npm/video.js@7.6.6/dist/video.js"></script>
    <!-- ChromeCast-->
    <link href="https://cdn.jsdelivr.net/npm/@silvermine/videojs-chromecast@1.2.0/dist/silvermine-videojs-chromecast.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@silvermine/videojs-chromecast@1.2.0/dist/silvermine-videojs-chromecast.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>
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
                <div class="video-container">
                    <?php
                    $result = $con->query($episode_query);

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

                    $result->free();
                    //add video to user history
                    $checkifexist_query = "SELECT * FROM `history` INNER JOIN `accounts` ON `accounts`.`id` = `history`.`id` WHERE `accounts`.`id` = $_SESSION[id]";
                    $result = $con->query($checkifexist_query);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $db = $row['last_watched'];
                            $db = rtrim($db, ';');
                            $episodes_list = explode(";", $row['last_watched']);
                            if (($key = array_search($id, $episodes_list)) !== false) {
                                unset($episodes_list[$key]);
                            }
                            $updated_list = [$id, ...$episodes_list];
                            $updated_list = array_slice($updated_list, 0, 4);
                            $update_query_content = implode(";", $updated_list);
                            $updateHistory_query = "UPDATE `history` SET `last_watched`='$update_query_content' WHERE `id` = $row[id]";
                            $con->query($updateHistory_query);
                        }
                    } else {
                        $addToHistory_query = "INSERT INTO `history`(`id`, `last_watched`) VALUES ($_SESSION[id],'$id')";
                        $con->query($addToHistory_query);
                    }
                    $result->free();
                    //get series info
                    $seriesInfo_query = "SELECT * FROM `series` WHERE `id` = $series_id";
                    $result = $con->query($seriesInfo_query);
                    if ($result->num_rows > 0) {
                        $res = $result->fetch_assoc();
                        $full_title = $res['title'];
                        $alt_title = $res['alt_title'];
                        $season = $res['season'];
                        $series_desc = $res['desc'];
                        $genre = $res['genre'];
                    }
                    //check if desc is empty
                    $epDesc = $desc;
                    if (empty($epDesc)) {
                        $epDesc = $series_desc;
                    }
                    //video exist, create player
                    echo <<< VIDEO_PLAYER
                            <video id="o-video" class="video-js vjs-big-play-centered vjs-16-9" controls preload="auto" poster="$poster">
                                <source src="$url" type="video/mp4" />
                                <p class="vjs-no-js">
                                    To view this video please enable JavaScript, and consider upgrading to a
                                    web browser that
                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                </p>
                            </video>
VIDEO_PLAYER;
                    echo "<div class='video-interactive'>";
                    include 'scripts/like-status.php';
                    include 'scripts/like-counter.php';
                    echo <<< VIDEO_INTERACTIVE
                        <a href="series.php?s=$series_id"><span class="mdi mdi-format-list-numbered"></span></a>
                        <span class="mdi mdi-download" onclick="download('$url', '{$alt_title} S{$season}O{$ep_number}'); return false;"></span>
                        <span class="mdi mdi-share" onclick="navigator.clipboard.writeText(window.location.href);"></span>
                    </div>
                    <div class="video-meta">
                        <h2>$alt_title S{$season} O{$ep_number} - "$title"</h2>
                        <p>$epDesc</p>
                    </div>
VIDEO_INTERACTIVE;
                    ?>
                    <div class="comments"></div>

                    <script>
                        const comments_page_id = '<?php echo $id; ?>';
                        fetch("scripts/comments.php?video_id=" + comments_page_id).then(response => response.text()).then(data => {
                            document.querySelector(".comments").innerHTML = data;
                            document.querySelectorAll(".comments .reply_comment_btn").forEach(element => {
                                element.onclick = event => {
                                    event.preventDefault();
                                    document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
                                    document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "']").style.display = 'block';
                                };
                            });
                            //report system
                            document.querySelectorAll(".comments .report_comment_btn").forEach(element => {
                                element.onclick = event => {
                                    event.preventDefault();
                                    $('#form-comment-id').val(element.getAttribute("data-comment-id"));
                                    $('.form-popup-bg').addClass('is-visible');
                                    $('.form-popup-bg').on('click', function(event) {
                                        if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) {
                                            event.preventDefault();
                                            $(this).removeClass('is-visible');
                                        }
                                    });

                                    function closeForm() {
                                        $('.form-popup-bg').removeClass('is-visible');
                                    }

                                    //element.getAttribute("data-comment-id"); //getuje id komentarza
                                };
                            });
                            document.querySelectorAll(".comments .write_comment form").forEach(element => {
                                element.onsubmit = event => {
                                    event.preventDefault();
                                    fetch("scripts/comments.php?video_id=" + comments_page_id, {
                                        method: 'POST',
                                        body: new FormData(element)
                                    }).then(response => response.text()).then(data => {
                                        element.parentElement.innerHTML = data;
                                    });
                                };
                            });
                        });
                    </script>
                </div>
                <div class="suggested">
                    <?php
                    $nextEp_query = "SELECT `episodes`.`id`, `episodes`.`poster`, `series`.`season`, `episodes`.`ep_number`
                    FROM `episodes`
                    INNER JOIN `series` ON `series`.`id` = `episodes`.`series_id`
                    WHERE `series`.`id` = (SELECT `episodes`.`series_id` from `episodes` WHERE `episodes`.`id` = $id)
                    AND `episodes`.`isActive` = 1
                    AND (
                        (`episodes`.`ep_number` = (SELECT `episodes`.`ep_number` from `episodes` WHERE `episodes`.`id` = $id) + 1)
                        OR (
                            `episodes`.`ep_number` > (SELECT `episodes`.`ep_number` from `episodes` WHERE `episodes`.`id` = $id)
                            AND NOT EXISTS (
                                SELECT *
                                FROM `episodes`
                                WHERE `series_id` = (SELECT `episodes`.`series_id` from `episodes` WHERE `episodes`.`id` = $id)
                                AND `ep_number` = (SELECT `episodes`.`ep_number` from `episodes` WHERE `episodes`.`id` = $id) + 1
                                AND `isActive` = 1
                            )
                        )
                    )
                    ORDER BY `episodes`.`ep_number`
                    LIMIT 1;
                    ";
                    $result = $con->query($nextEp_query);
                    if ($result->num_rows > 0) {
                        $res = $result->fetch_assoc();
                        $nextEp_id = $res['id'];
                        $nextEp_poster = $res['poster'];
                        $nextEp_season = $res['season'];
                        $nextEp_num = $res['ep_number'];
                        echo <<< NEXT_EP
                            <h2>Kolejny odcinek:</h2>
                            <a class="next-ep-box" href="watch.php?v=$nextEp_id">
                                <img width="100%" src="$nextEp_poster">
                                <div class="next-ep-title">$alt_title S{$nextEp_season} O{$nextEp_num}</div>
                            </a>
NEXT_EP;
                    }
                    $result->free();
                    ?>
                    <h2>Podobne serie:</h2> <!-- wybierz ten sam gatunek group by sezon -->
                    <div class="sugested-ep-box"></div>
                    <div class="sugested-ep-box"></div>
                    <div class="sugested-ep-box"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-popup-bg">
        <div class="form-container">
            <span id="btnCloseForm" class="mdi mdi-close "></span>
            <h1>Zgłoś komentarz</h1>
            <form id="report-form">
                <input type="text" id="form-comment-id" name="comment-id" hidden>
                <div class="form-group">
                    <label>Podaj powód</label>
                    <select class="form-control" name="reason">
                        <option>Obraza</option>
                        <option>Reklama</option>
                        <option>Spam</option>
                        <option>Inny</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dodatkowe uwagi: (opcjonalnie)</label>
                    <textarea maxlength="1024" rows="3" class="form-control" type="text" name="note"></textarea>
                </div>
                <button type="submit">Prześlij zgłoszenie</button>
            </form>
        </div>
    </div>

    <script>
        var options;
        options = {
            techOrder: ['chromecast', 'html5'],
        };
        var player = videojs('o-video', options);
        player.chromecast();
        var endIntroTime = "<?php echo $intro_end; ?>";
        if (!isNaN(endIntroTime) && Number(endIntroTime) > 0) {
            var myButton = player.getChild('ControlBar').addChild('button', {}, 3);
            var myButtonDom = myButton.el();
            myButtonDom.innerHTML = '<span class="mdi mdi-debug-step-over"></span>';
            myButtonDom.onclick = function() {
                player.currentTime(endIntroTime);
            };
        }
    </script>
    <script src="scripts/dropdown.js"></script>
</body>

</html>