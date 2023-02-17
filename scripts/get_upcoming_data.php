<?php
require_once 'isloggedin.php';
require_once 'db_con.php';

$sql = "SELECT `brd-start`, `alt_title`, `id` FROM `series` ORDER BY `brd-start` ASC";
$result = $con->query($sql);

$animeData = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $animeData[] = array(
            'name' => $row['alt_title'],
            'date' => $row['brd-start'],
            'id' => $row['id']
        );
    }
}

$con->close();

echo json_encode($animeData);
