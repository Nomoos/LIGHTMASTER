<?php
session_start();
include "variables.php";
//Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky!  
if (isset($_GET['p'])) {
    $_SESSION['page'] = $_GET['p'];
    header("Location: " . $_SERVER['SERVER_ROOT'] . "");
    die();
}

if (isset($_GET['c'])) {
    $_SESSION['company'] = $_GET['c'];
    header("Location: " . $_SERVER['SERVER_ROOT'] . "");
if (isset($_GET['action'])) {
    if ($_GET['action'] == 1) {
        $_SESSION['page'] = 'map';
    }elseif($_GET['action'] == 2) {
        $_SESSION['page'] = 'copanyprofile';
    }else{

    }
} else {
//if action non set
}
    die();
}

if (isset($_GET['z'])) {
    $_SESSION['zone'] = $_GET['z'];
    header("Location: " . $_SERVER['SERVER_ROOT'] . "");
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 1) {
            $_SESSION['zoneshow'] = 1;
            $_SESSION['page'] = 'map';
        } else {

        }
    } else {
//if action non set
        $_SESSION['zoneshow'] = '';
        unset($_SESSION['zoneshow']);
    }
    die();
}


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'odhlasit_se') {
        $_SESSION['zone'] = '';
        $_SESSION['page'] = '';
        $_SESSION['login'] = '';
        $_SESSION['heslo'] = '';
        $_SESSION['id'] = '';
        $_SESSION['company'] = '';
        $_SESSION['zoneshow'] = '';
        unset($_SESSION['zoneshow']);
        unset($_SESSION['login']);
        unset($_SESSION['heslo']);
        unset($_SESSION['id']);
        unset($_SESSION['page']);
        unset($_SESSION['zone']);
        unset($_SESSION['company']);

        /***************NOVÉ*********************/

        setcookie("auto", "", time() + 9999999);

        /***************KONEC NOVÉHO*********************/
    }
}
//Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
require_once 'db.php';

if (!array_key_exists("page", $_SESSION)){

/***************NOVÉ*********************/

// Pokud v COOKIE jsou proměnné pro automatické přihlášení 
if (isset($_COOKIE['auto']) AND isset($_COOKIE['login']) AND isset($_COOKIE['password'])) {
    if ($_COOKIE['auto'] == 'yes') {
// Nadefinujeme hned potřebné proměnné SESSION
        $_SESSION['heslo'] = strrev(md5($_COOKIE['password'])) . "g5ds8";
        $_SESSION['login'] = $_COOKIE['login'];
        $_SESSION['id'] = $_COOKIE['id'];
    }
}

/***************KONEC NOVÉHO*********************/

// V případě, že session nejsou prázdné a obsahují heslo a login, vybereme z databáze avatar tohoto uživatele
if (!empty($_SESSION['login']) and !empty($_SESSION['heslo'])) {
    $login = $_SESSION['login'];
    $heslo = $_SESSION['heslo'];
    $q_ava = mysqli_query($dataconection, "SELECT `id`,`avatar` FROM `users` WHERE `login` = '" . $login . "' AND `pass` = '" . $heslo . "'");
    $r_ava = mysqli_fetch_array($q_ava, MYSQLI_ASSOC);
}


echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Přihlašení uživatele</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" >

<link rel="stylesheet" href="' . $_SERVER['SERVER_ROOT'] . '/css/css/kraken.css" >';
?>
<style>
    .container {
        width: 20%;
        margin-left: auto;
        margin-right: auto;
    }

    .back {
        font-size: 10pt;
        margin-top: 5px;
    }

    form {
        margin-bottom: 5px
    }

    .login {
        margin-bottom: 5px
    }
    
    .headcontainer{
        margin-left: auto;
    margin-right: auto;
    width: 550px;
    }
    .flag {
    width: 30px;
}
.flags{
float: right;
}
    .description{
      margin-left: 50px;
    }
    .loginfail{
        color:red;
        font-weight: bold;
    }
    .projectname{
        font-weight: bold ;
    }

</style>

</head>

<body>

<?php
//Ověřujeme, zda byla vybrána nějaka data z databáze ( avatar ). Jestli ne v COOKIE jsou špatné heslo nebo login.
if (empty($r_ava['avatar']) OR !isset($r_ava['avatar']) OR $r_ava['avatar'] == '') {
    echo '
    <div class="headcontainer">
    <img class="logolight" src="'.$_SERVER['SERVER_ROOT'].'img/logolight.png" alt="Lightmaster logo"><div class="flags"><div class="projectname">Projekt: ME1 – C2M</div><img class="flag" src="'.$_SERVER['SERVER_ROOT'].'img/countryflags/cz.png" alt="Czech flag"><img class="flag" src="'.$_SERVER['SERVER_ROOT'].'img/countryflags/en.png" alt="English flag"></div>
    <div class="description">Řídící a monitorovací systém pro pouliční LED osvětlení třídy ME1.</div>
    </div>
<div class="container">';
    if(isset($_SESSION['loginfail'])) {
        echo '<div class="loginfail">Chybné uživatelské jméno nebo heslo.</div>';
        unset($_SESSION['loginfail']);
    }

    if(isset($_SESSION['registrationfail'])) {
        echo '<div class="loginfail">'.$_SESSION['registrationfail'].'</div>';
        unset($_SESSION['registrationfail']);
    }

    echo '
<h1>Přihlásit se</h1>
<form action="overeni.php" method="POST">
<!--  overeni.php je skript pro ověření zadaných dat, tzn. po klíknutí na tlačítko Ok se data pošlou do tohoto souboru metodou POST  -->

	<div><label for="login">Login:</label></div>';
// Jestli existuje proměnná COOKIE['login']. Bude jestli uživatel při předchozím přihlášení zaškrtnul políčko "Zapamatovat mě"   
    if (isset($_COOKIE['login'])) {
// Do atributu value přidame hodnotu proměnné COOKIE s loginem
        echo '<div><input type="text" name="login" value="' . $_COOKIE['login'] . '"></div>';
    } else {
        echo '<div><input type="text" name="login" ></div>';
    }

    echo '	<div><label for="heslo">Heslo:</label></div>';
// Jestli existuje proměnná COOKIE['login']. Bude jestli uživatel při předchozím přihlášení zaškrtnul políčko "Zapamatovat mě"   
    if (isset($_COOKIE['password'])) {
// Do atributu value přidame hodnotu proměnné COOKIE s heslem
        echo '<div><input type="password" name="heslo" value="' . $_COOKIE['password'] . '"></div>';
    } else {
        echo '<div><input type="password" name="heslo" ></div>';
    }

    echo '
<div><input name="save" type="checkbox" value="1">Zapamatovat si mě</div>

<!--------------NOVÉ---------------------->

<div><input name="autovstup" type="checkbox" value="1">Automatické přihlášení.</div>

<!--------------KONEC NOVÉHO---------------------->

<div><input class="login" type="submit" name="go" value="Přihlásit se"></div>
</form>';


    echo '<div class="back"><input class="button" type="button" onclick=\'window.location.href="registration.php";\' value="Nová registrace"><a class="link" type="button" onclick=\'window.location.href="new_pass.php";\'>Zapomněli jste heslo?</div>';

} else {
    require_once 'pristup.php';
    $_SESSION['page'] = $PAGEAFTERLOGGIN;
    echo "<script>location=" . $_SERVER['SERVER_ROOT'] . "</script>";
}
//když je nastavená stránka
} else {
    require_once 'pristup.php';
//old php on web lightmaster php 5.4+ use $pages = ["map" => "map.php"]
    $pages = array(
        "map" => "map.php",
        "lamps" => "lamps.php",
        "stats" => "stats.php",
        "plans" => "plans.php",
        "users" => "users.php",
        "company" => "company.php",
        "copanyprofile" => "company_profile.php",
        "zones" => "zones.php",
    );
    $pageset = 0;
    foreach ($pages as $key => $value) {

        if ($_SESSION['page'] == $key) {
            //echo $key;
            require_once $value;
            $pageset = 1;
        }
    }
    if (0 == $pageset) {
        $_SESSION['page'] = '';
        unset($_SESSION['page']);
        http_response_code(404);
        require_once $NOTFOUNDPAGE;

    }
echo $_SESSION['page'];
}
?>

</body>
</html>
