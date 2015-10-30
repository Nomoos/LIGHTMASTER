<?php

$DBSERVER = 'localhost';
$DBUSER = 'root';
$DBPASS = 'vertrigo';

$DB = 'lamps.lightmaster';


$dataconection = new mysqli($DBSERVER,$DBUSER,$DBPASS, $DB);
if ($dataconection->connect_errno) {
    echo "Failed to connect to MySQL: (" . $dataconection->connect_errno . ") " . $dataconection->connect_error;
}
//echo $dataconection->host_info . "\n";
mysqli_query($dataconection, "SET NAMES utf8");


?>
