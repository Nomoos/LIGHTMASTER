<?php
// Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky! 
session_start();
header('Content-type: text/html;charset=UTF-8');

// Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
require_once 'db.php';

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
				
// Uložíme identifikator příjemce do proměnné $id	
				if(isset($_POST['id']) AND $_POST['id']!='')
				{
					$id = stripslashes(htmlspecialchars(trim($_POST['id'])));
					
					if(isset($_POST['zprava']))
					{
						$zprava = stripcslashes(htmlspecialchars(trim($_POST['zprava'])));
						if($zprava == '')
						{
							unset($zprava);
						}
					}
					
					if(isset($_POST['prijemce']))
					{
						$prijemce = stripcslashes(htmlspecialchars(trim($_POST['prijemce'])));
						if($prijemce == '')
						{
							unset($prijemce);
						}
					}
					
					$odesilatel = $_SESSION['login'];
					
					if(empty($zprava) OR empty($prijemce) OR empty($odesilatel))
					{
						exit("Vyplňte všechna pole");
					}
					
					$query = mysql_query("INSERT INTO `message`(`odesilatel`,`prijemce`,`date`,`zprava`) VALUES('".$odesilatel."','".$prijemce."',NOW(),'".$zprava."')");
					if(!$query) 
					{
						echo "<script>alert('Zpráva se neodeslala.')</script>";
						header("Refresh: 0; url=uzivatel.php?id=".$_SESSION['id']."");
					} else {
						echo "<script>alert('Zpráva se odeslala vpořádku.')</script>";
						header("Refresh: 0; url=uzivatel.php?id=".$_SESSION['id']."");
					}
					
				} else {
					exit("Není jasné kdo je příjemce.");
				}
			} else {
// Jinak zobrazíme chybu
				exit("Vstup na tuto stránku je povolen pouze přihlášeným uživatelům.");
			}
		
} else {
// Pokud proměnné neexistují zobrazíme chybu
	exit("Vstup na tuto stránku je povolen pouze přihlášeným uživatelům.");
}
?>
