<?php
header('Content-type: text/html;charset=UTF-8'); 
//Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )		
require_once 'db.php';

if(isset($_POST['submit']))
{
	
	if(isset($_POST['email']))
	{
		$email = stripcslashes(htmlspecialchars(trim($_POST['email'])));
		if($email == '')
		{
				unset($email);
		}
	}
  
	if(isset($_POST['login']))
	{
		$login = stripcslashes(htmlspecialchars(trim($_POST['login'])));
		if($login == '')
		{
				unset($login);
		}
	}
	
	if(!preg_match("/^[a-z0-9_-]{1,20}+(\.){0,20}+([a-z0-9_-]){0,20}@(([a-z0-9-]+\.)+(com|net|org|mil|".
	"edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-".
	"9]{1,3}\.[0-9]{1,3})$/is",$email)) 
	{
		exit    ("E-mail není platný!");
	}
	
	if(empty($login) or empty($email)) 
	{
		exit("Vyplňte všechna pole");
	}
	
	if(isset($login) AND isset($email))
	{
		
// Vybereme z databáze identifikátor uživatele s zadaným loginem a emailem a ověříme, zda je jeho účet aktivován
		$query = mysql_query("SELECT `id` FROM `users` WHERE `login`='".$login."' AND `email`='".$email."' AND `activation`=1");
		if(mysql_num_rows($query)!=0)
		{
// Vygenerujeme nové heslo, do proměnné $date uložíme dnešní datum a čas
			$date = date('YmdHis');
// použijeme md5 šifrování
			$new_password = md5($date);
// vybereme 6 symbolů
			$new_password = substr($new_password,2,6);
// Zašifrujeme ho jako obvykle a uložíme do DB		
			$new_password_enc = strrev(md5($new_password))."g5ds8";
			$query1 = mysql_query("UPDATE `users` SET `pass`='".$new_password_enc."' WHERE `login`='".$login."'");
			if(!$query1) { echo mysql_error() . " - " . mysql_errno(); }
			else {
// Pošleme uživateli e-mail s novým heslem				
				$_to = $email;
				
				$_subject = "Obnovení hesla";
				
				$_message = "<div style=\"font-size:11pt; font-family:Times New Roman; color:black; padding:5px;\"><div>Dobrý den,</div>";
				$_message .= "<div style=\"margin:10px 0;\">Váše nové heslo je: ".$new_password."</div>";
				$_message .= "<div>S pozdravem, administrace lightmaster.cz</div>";
				
				$_headers  = 'MIME-Version: 1.0' . "\r\n";
				$_headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$_headers .= 'From: <info@koding.cz>' . "\r\n";
	
				if(@mail($_to, '=?UTF-8?B?'.base64_encode($_subject).'?=', $_message, $_headers))
				{
					echo "E-mail s novým heslem byl odeslán. <a href=\"index.php\">Hlavní stránka</a>";
				} else {
					echo "E-mail nebyl odeslán. Zkuste to za 5 minut. <a href=\"index.php\">Hlavní stránka</a>";
				}
	
			}
			
		} else {
			exit("Uživatel s tímto e-mailem neexistuje.");
		}
	}
	
} else {
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <link rel="stylesheet" href="css/css/kraken.css" />
 <style>
.container{
width: 20%;
margin-left: auto;
margin-right: auto;
}
.back{
	font-size:10pt; 
	margin-top:20px;
}

</style>
<head>
	<title>Nové heslo</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<div class="container">
<h2>Obnovit heslo</h2>
<form action="" method="POST">

<div><label for="login">Váš login:</label></div>
<div><input type="text" name="login"></div>

<div><label for="email">Váš e-mail:</label></div>
<div><input type="text" name="email"></div>

<div><input type="submit" name="submit" value="Odeslat"></div>

</form>

<div class="back"><a href="index.php">přihlasit se</a></div>
</div>
</body>
</html>
';
}
?>
