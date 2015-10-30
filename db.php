<?php

$DBSERVER = 'localhost';
$DBUSER = 'root';
$DBPASS = 'vertrigo';

$DB = 'lamps.lightmaster';

$link = mysql_connect($DBSERVER,$DBUSER,$DBPASS) or die ('I cannot connect');
mysql_select_db($DB,$link) or die ('I cannot select DB');
mysql_query("SET NAMES utf8");

?>
