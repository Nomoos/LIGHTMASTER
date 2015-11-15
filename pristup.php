<?php
function die_with($message)
{
        echo "<script>console.log('".$message."');</script>";
        $_SESSION['zone']='';
        $_SESSION['page']='';
      	$_SESSION['login']='';
      	$_SESSION['heslo']='';
      	$_SESSION['id']='';
      	unset($_SESSION['login']);
      	unset($_SESSION['heslo']);
      	unset($_SESSION['id']);
        unset($_SESSION['page']);
      	unset($_SESSION['zone']);
				echo '<script>window.location.href="'.$_SERVER['SERVER_ROOT'].'";</script>;';
        die();
}

// Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky! 
//session_start();
//header('Content-type: text/html;charset=UTF-8');

// Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
//require_once 'db.php';

// Pokud není parametr id v URL prázdný, uložime do proměnné $id hodnotu $_GET['id'] ( čemu se rovná id v URL ) 

// Ověřme, zda existujou proměnné $_SESSION['login'] a $_SESSION['heslo'].
		if(!empty($_SESSION['login']) AND !empty($_SESSION['heslo']))
		{
// Dál ověřime, zda jsou tyto údaje platné
			$login = $_SESSION['login'];
			$heslo = $_SESSION['heslo'];
			
			$over = mysqli_query($dataconection,"SELECT `id` FROM `users` WHERE `login`='".$login."' AND `pass`='".$heslo."'");
			
// Pokud najdeme identifikator s tímto loginem a heslem uložime ho do pole $res_over
			if(mysqli_num_rows($over)!=0)
			{
				$res_over = mysqli_fetch_assoc($over);
				
// Ověřime, zda se jedna o registrovaného uživatele
				
				$query = mysqli_query($dataconection,"SELECT * FROM `users` WHERE `id`='".$_SESSION['id']."'");
				if(mysqli_num_rows($query)!=0)
				{
// Pokus je uživatel přihlášený uložime data o něm do pole $result

					$result = mysqli_fetch_assoc($query);					
				} else {									
        die_with('User does not exist.');
				}
				
			} else {
      // Jinak zobrazime chybu				
        die_with('User does not exist.');
			}
		} else {
        die_with('SESSION does not exist.');
		}
    ?>

