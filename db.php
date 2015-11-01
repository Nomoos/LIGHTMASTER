<?php

$DBSERVER = 'vpn2.atema.cz';
$DBUSER = 'lamps.light';
$DBPASS = 'i0tgJvPMOV';

$DB = 'lamps.lightmaster2';

$link = mysql_connect($DBSERVER,$DBUSER,$DBPASS) or die ('I cannot connect');
mysql_select_db($DB,$link) or die ('I cannot select DB');
mysql_query("SET NAMES utf8");

?>
