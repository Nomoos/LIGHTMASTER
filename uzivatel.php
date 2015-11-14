<?php
// Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky! 
session_start();
header('Content-type: text/html;charset=UTF-8');

// Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
require_once 'db.php';

// Pokud není parametr id v URL prázdný, uložime do proměnné $id hodnotu $_GET['id'] ( čemu se rovná id v URL ) 
if(isset($_GET['id']) AND $_GET['id']!='')
{
	$id = stripslashes(htmlspecialchars(trim($_GET['id'])));
// Napišeme jednoduchý regulární výraz ověřující, zda id je číslo
	if(!preg_match("|^[\d]+$|", $id))
	{
		exit("Id musí být číselná hodnota.");
	} else {
// Ověřme, zda existujou proměnné $_SESSION['login'] a $_SESSION['heslo'].
		if(!empty($_SESSION['login']) AND !empty($_SESSION['heslo']))
		{
// Dál ověřime, zda jsou tyto údaje platné
			$login = $_SESSION['login'];
			$heslo = $_SESSION['heslo'];
			
			$over = mysqli_query($dataconection, "SELECT `id` FROM `users` WHERE `login`='".$login."' AND `pass`='".$heslo."'");
			
// Pokud najdeme identifikator s tímto loginem a heslem uložime ho do pole $res_over
			if(mysqli_num_rows($over)!=0)
			{
				$res_over = mysqli_fetch_assoc($over);
				
// Ověřime, zda se jedna o registrovaného uživatele
				
				$query = mysqli_query($dataconection, "SELECT * FROM `users` WHERE `id`='".$id."'");
				if(mysqli_num_rows($query)!=0)
				{
// Pokus je uživatel přihlášený uložime data o něm do pole $result

					$result = mysqli_fetch_assoc($query);					
				} else {
					exit("Uživatel neexistuje.");
				}
				
			} else {
// Jinak zobrazime chybu
				exit("Vstup na tuto stránku je povolen pouze přihlášeným uživatelům.");
			}
		} else {
			exit("Vstup na tuto stránku je povolen pouze přihlášeným uživatelům.");	
		} 
		
	}


} else { exit("Špatný parametr v URL."); }

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>'.$result['login'].'</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" >
  
<link rel="stylesheet" href="/www2/css/css/kraken.css" />	



</head>
<body>
<div class="container">

<h1>Ahoj '.$result['login'].'</h1>';

// Navigace, jsou v ní prvky, které ještě přidáme
require_once 'module/usermenu.php';
require_once 'module/menu.php';   
if($result['login'] == $login){

}else{
echo "NEFUNKCNI PRIHLASENI";
}

// Pokud stránka patří uživateli nabízíme mu změnit údaje a zobrazime jeho soukromé zprávy
if($result['login'] == $_SESSION['login'])
{
	echo '<br>
	<form action="update_user.php" method="POST">
            <div><label for="login">Váše přihlášovací jméno: <strong>'.$result['login'].'</strong> - změnit jméno:</label></div>
            <div><input name="login" type="text">
            <input type="submit" name="submit" value="změnit"></div>
            </form>
            <br>
	<form action="update_user.php" method="POST">
            <div><label for="heslo">Změnit heslo:</label></div>
            <div><input name="heslo" type="password">
            <input type="submit" name="submit" value="změnit"></div>
            </form>
            <br>
	<form action="update_user.php" method="POST" enctype="multipart/form-data">
			<div><label for="avatar">Váš avatar:</label></div>
            <div><img alt="avatar" src=avatar/'.$result['avatar'].'><br>
            Obrázek musi být ve formatu jpg, gif nebo png. Změnit avatar:</div>
            <div><input type="FILE" name="avatar">
            <input type="submit" name="submit" value="změnit"></div>
            </form>
            <br>';
} else {
// Pokud jsme na cizí, pak zobrazíme jenom data o tom kdo to je a formulář na odesílaní zpráv.
echo '';
}
echo '</div></body>
</html>';
?>
