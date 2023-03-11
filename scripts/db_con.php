<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'getsu';
/* 
$DATABASE_HOST = 'sql108.epizy.com';
$DATABASE_USER = 'epiz_33759093';
$DATABASE_PASS = 'HjV5Vg38CAN';
$DATABASE_NAME = 'epiz_33759093_getsu';
*/
$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    header('Location: 404.php?' . mysqli_connect_error());
    exit;
}
