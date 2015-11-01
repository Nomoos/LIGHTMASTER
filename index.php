<?php
session_start(); 
include "variables.php";
//Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky!  

if (isset($_GET['action'])){
if($_GET['action']=='odhlasit_se'){

	$_SESSION['login']='';
	$_SESSION['heslo']='';
	$_SESSION['id']='';
	unset($_SESSION['login']);
	unset($_SESSION['heslo']);
	unset($_SESSION['id']);
	
/***************NOVÉ*********************/

	setcookie("auto", "", time()+9999999);
	
/***************KONEC NOVÉHO*********************/

}
}

//Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
require_once 'db.php';
 
 
/***************NOVÉ*********************/
 
// Pokud v COOKIE jsou proměnné pro automatické přihlášení 
if (isset($_COOKIE['auto']) AND isset($_COOKIE['login']) AND isset($_COOKIE['password']))
{
	if ($_COOKIE['auto'] == 'yes') 
    { 
// Nadefinujeme hned potřebné proměnné SESSION
        $_SESSION['heslo'] = strrev(md5($_COOKIE['password']))."g5ds8"; 
        $_SESSION['login'] = $_COOKIE['login'];
		$_SESSION['id'] = $_COOKIE['id'];
    }        
}
 
/***************KONEC NOVÉHO*********************/
 
// V případě, že session nejsou prázdné a obsahují heslo a login, vybereme z databáze avatar tohoto uživatele
if(!empty($_SESSION['login']) and !empty($_SESSION['heslo']))
{
	$login = $_SESSION['login'];
    $heslo = $_SESSION['heslo'];
    $q_ava = mysqli_query($dataconection,"SELECT `id`,`avatar` FROM `users` WHERE `login` = '".$login."' AND `pass` = '".$heslo."'"); 
    $r_ava = mysqli_fetch_array($q_ava,MYSQLI_ASSOC);
}


echo'
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Přihlašení uživatele</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" >

<link rel="stylesheet" href="'.$_SERVER['SERVER_ROOT'].'/css/css/kraken.css" >';
?>
<style>
.container{
width: 20%;
margin-left: auto;
margin-right: auto;
}
.back{
	font-size:10pt;
	margin-top:5px;
}
form{
  margin-bottom:5px
}
.login{
 margin-bottom:5px 
}

</style>
	
</head>

<body>

<?php
//Ověřujeme, zda byla vybrána nějaka data z databáze ( avatar ). Jestli ne v COOKIE jsou špatné heslo nebo login.
if(empty($r_ava['avatar']) OR !isset($r_ava['avatar']) OR $r_ava['avatar']=='')
{
echo '
<div class="container"> 
<h1>Přihlaste se</h1>
<form action="overeni.php" method="POST">
<!--  overeni.php je skript pro ověření zadaných dat, tzn. po klíknutí na tlačítko Ok se data pošlou do tohoto souboru metodou POST  -->

	<div><label for="login">login:</label></div>';
// Jestli existuje proměnná COOKIE['login']. Bude jestli uživatel při předchozím přihlášení zaškrtnul políčko "Zapamatovat mě"   
	if(isset($_COOKIE['login']))
	{
// Do atributu value přidame hodnotu proměnné COOKIE s loginem
		echo '<div><input type="text" name="login" value="'.$_COOKIE['login'].'"></div>';
	} else {
		echo '<div><input type="text" name="login" ></div>';
	}	
	
echo '	<div><label for="heslo">Heslo:</label></div>';
// Jestli existuje proměnná COOKIE['login']. Bude jestli uživatel při předchozím přihlášení zaškrtnul políčko "Zapamatovat mě"   
	if(isset($_COOKIE['password']))
	{
// Do atributu value přidame hodnotu proměnné COOKIE s heslem
		echo '<div><input type="password" name="heslo" value="'.$_COOKIE['password'].'"></div>';
	} else {
		echo '<div><input type="password" name="heslo" ></div>';
	}
           
echo '
<div><input name="save" type="checkbox" value="1">Zapamatovat mě.</div>

<!--------------NOVÉ---------------------->

<div><input name="autovstup" type="checkbox" value="1">Automatickié přihlášení.</div>

<!--------------KONEC NOVÉHO---------------------->

<div><input class="login" type="submit" name="go" value="Přihlásit"></div>
</form>';

	
echo '<div class="back"><input class="button" type="button" onclick=\'window.location.href="registration.php";\' value="Registrace" style="
    float: left;
    width: 49%;
"><input class="button" type="button" onclick=\'window.location.href="new_pass.php";\' value="Zapomněli jste heslo?" style="
    float: right;
    width: 49%;
"></div>'; 

/*--------------NOVÉ----------------------*/

//echo '<br><div class="back"><a href="new_pass.php">Zapomněli jste heslo?</a></div></div>';   

/*--------------KONEC NOVÉHO----------------------*/
	
} else {
echo '<script>
window.location.href="map.php";
</script>;';      
}
?>
	
</body>
</html>
