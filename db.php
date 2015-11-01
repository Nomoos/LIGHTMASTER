<?php

$DBSERVER = 'vpn2.atema.cz';
$DBUSER = 'lamps.light';
$DBPASS = 'i0tgJvPMOV';

$DB = 'lamps.lightmaster2';


$dataconection = new mysqli($DBSERVER,$DBUSER,$DBPASS, $DB);
if ($dataconection->connect_errno) {
    echo "Failed to connect to MySQL: (" . $dataconection->connect_errno . ") " . $dataconection->connect_error;
}
//echo $dataconection->host_info . "\n";
mysqli_query($dataconection, "SET NAMES utf8");


?>
