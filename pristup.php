<?php
// Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky! 
session_start();
header('Content-type: text/html;charset=UTF-8');

// Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
require_once 'db.php';

// Pokud není parametr id v URL prázdný, uložime do proměnné $id hodnotu $_GET['id'] ( čemu se rovná id v URL ) 

// Ověřme, zda existujou proměnné $_SESSION['login'] a $_SESSION['heslo'].
		if(!empty($_SESSION['login']) AND !empty($_SESSION['heslo']))
		{
// Dál ověřime, zda jsou tyto údaje platné
			$login = $_SESSION['login'];
			$heslo = $_SESSION['heslo'];
			
			$over = mysql_query("SELECT `id` FROM `users` WHERE `login`='".$login."' AND `pass`='".$heslo."'");
			
// Pokud najdeme identifikator s tímto loginem a heslem uložime ho do pole $res_over
			if(mysql_num_rows($over)!=0)
			{
				$res_over = mysql_fetch_assoc($over);
				
// Ověřime, zda se jedna o registrovaného uživatele
				
				$query = mysql_query("SELECT * FROM `users` WHERE `id`='".$_SESSION['id']."'");
				if(mysql_num_rows($query)!=0)
				{
// Pokus je uživatel přihlášený uložime data o něm do pole $result

					$result = mysql_fetch_assoc($query);					
				} else {
					exit("Uživatel neexistuje.");
				}
				
			} else {
// Jinak zobrazime chybu
				echo '<script>window.location.href="/www2";</script>;';
			}
		} else {
				echo '<script>window.location.href="/www2";</script>;';	
		}
    ?>

