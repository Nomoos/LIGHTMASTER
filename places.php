<?php
header('Content-type: text/html;charset=UTF-8');
//Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )		
require_once 'db.php';
if (isset($_POST['submit'])) {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<link rel="stylesheet" href="http://openlayers.org/en/v3.2.1/css/ol.css" type="text/css">
    <style>
      .map {
        height: 400px;
        width: 80%;
      }
    </style>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" >
  <script src="http://openlayers.org/en/v3.2.1/build/ol.js" type="text/javascript"></script>
</head>
<body>
';
    $rules = array();
    foreach ($_POST as $kluc => $hodnota) {
        $retezec = explode("*", $kluc);

        if (count($retezec) == 2) {
            if (isset($rules[$retezec[0]]) == 0) {
                $rules[$retezec[0]] = array();
            }
            $rules[$retezec[0]][$retezec[1]] = "1";

        }
    }
    print_r($rules);
    foreach ($rules as $account => $info) {
        foreach (Array("Super_admin", "View_lamp", "Edit_lamp", "Edit_rule") as $key) {
            if (array_key_exists($key, $info)) {
            } else {
                $info[$key] = 0;
            }
        }


        $first = True;
        foreach ($info as $name => $rule) {
            if ($first) {
                $temp = $name . "=" . $rule;
                $first = False;
            } else {
                $temp = $temp . "," . $name . "=" . $rule;
            }
        }

        $sql = "UPDATE `Rule_access`SET " . $temp . "\n"
            . "WHERE ID_user= '" . $account . "'";
        mysqli_query($dataconection, $sql);
        echo $sql;
        echo "<br>" . $account . " - " . $temp . "<br>";

    }

    /* cyklus prebehne cele pole, v premennej $kluc bude nazov kluca, v premennej
   $hodnota bude hodnota prvku pola prisluchajuca aktualnemu klucu */
    echo "</body></html>";

} else {

    require_once 'pristup.php';

    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <style>
      .map {
        height: 400px;
        width: 80%;
      }
    </style>
  <title>' . $result['login'] . '</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" >
</head>
<body>
';
    $_SERVER['SERVER_ROOT'] = '/www2/';

    require_once 'module/usermenu.php';

    echo '<div><a href="uzivatel.php?id=' . $_SESSION['id'] . '">Můj profil</a>
| <a href="index.php">Hlavní stránka</a> 
| <a href="all_users.php">Seznam uživatelů</a> 
| <a href="index.php?action=odhlasit_se">Odhlásit se</a></div>';
}
?>
