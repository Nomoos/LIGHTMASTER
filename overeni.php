<?php

session_start();
include "variables.php";
//Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky!  
header('Content-type: text/html;charset=UTF-8'); 
if(isset($_POST['go'])){
//jestli tlačítko bylo zmačknuté tak jdi dál, jinak budete přesměrování na stránku pro registraci nového uživatele

	
	if(isset($_POST['login']))
	{
		$login = stripcslashes(htmlspecialchars(trim($_POST['login'])));
		if($login == '')
		{
				unset($login);
		}
	}
//uložime do proměnné $login login, který uživatel zadál ve formuláři, 
//a hned ji ošetříme několika funkcemi, aby ani tagy ani skripty nefungovali a odstraníme mezery ( trim ) 
//Jestli je proměnná prázdna tak ji odstraníme.


	if(isset($_POST['heslo']))
	{
		$heslo = stripcslashes(htmlspecialchars(trim($_POST['heslo'])));
		if($heslo == '')
		{
				unset($heslo);
		}
	}
//uložime do proměnné $heslo heslo, které uživatel zadál ve formuláři, 
//a hned ji ošetříme několika funkcemi, aby ani tagy ani skripty nefungovali a odstraníme mezery ( trim ) 
//Jestli je proměnná prázdna tak ji odstraníme.


//V případě, že jakákoli proměnná je prázdna, zastavíme skript a zobrazime hlášení		
	if(empty($login) or empty($heslo))
	{
		exit("Vyplňte všechna pole");
	}

//Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )		
	require_once 'db.php';




//--------------NOVÉ------------------------------------------//

// Zjistime ip 
	$ip=getenv("HTTP_X_FORWARDED_FOR");
    if(empty($ip) || $ip=='unknown') { $ip=getenv("REMOTE_ADDR"); } 

// Odstraňujeme ip uživatelů, kteři chybovali při přihlášení, po 15 minutách                       
	mysql_query("DELETE FROM `chyba` WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`date`) > 900");
	
// Vypišeme z databáze množstvi chybných pokusů přihlášení u uživatele s určitým ip 
	$error = mysql_query("SELECT `errors` FROM `chyba` WHERE `ip`='".$ip."'");
    $r_error = mysql_fetch_assoc($error);

	if($r_error['errors'] > 2) 
	{
// V případě, že chybných přihlášení je víc jak 2, zobrazíme hlášení
		exit("Zadali jste nesprávně uživatelské jméno nebo heslo 3 krát. Počkejte 15 minut pro další pokus.");
    }          
	
// Zašifrujeme heslo pomocí algoritmu md5       
	$heslo    = md5($heslo); 
// Ještě přidáme reverzí       
	$heslo    = strrev($heslo); 

// můžete přidat několik sybmolů, např. "g5ds8". V případě, že takto ošetřené heslo někdo bude chtít prolomit Brute Forcem ( hádání hesla )
// a použijí k tomu md5, pak se ničeho nedocíli. Můžete taky přidat symboly na začátku nebo uprostřed hesla.          
	$heslo    = $heslo."g5ds8";

			
/***************NOVÉ*********************/


// Vypišeme z databáze veškera data o uživateli s zadaným loginem a heslem
	$q1 = mysql_query("SELECT * FROM `users` WHERE `login`='".$login."' AND `pass`='".$heslo."' AND `activation`=1");
	
	
/***************KONEC NOVÉHO*********************/
	
	if(!$q1) { echo mysql_error() . ' - ' . mysql_errno(); }
	else
	{
		$r1 = mysql_fetch_assoc($q1);
/*
		if(mysql_num_rows($q1) == 0){
*/
		if(empty($r1['id']))
		{
// V případě, že uživatel neexistuje, přidame záznam do tabulky chyba, že uživatel s touto ip adresou se nepřihlásil  
			$sel_ip = mysql_query("SELECT `ip` FROM `chyba` WHERE `ip`='".$ip."'");
			$r_ip = mysql_fetch_row($sel_ip);
			
// Ověřujeme, zda uživatel jíž není v tabulce
			if($ip == $r_ip[0])
			{
				$sel_errors = mysql_query("SELECT `errors` FROM `chyba` WHERE `ip`='".$ip."'");
				$r_errors = mysql_fetch_assoc($sel_errors);
// Přidame ještě jeden nepovedený pokus
				$count = $r_errors['errors'] + 1;
				
// Dále aktualizujeme tabulku chyba
				mysql_query("UPDATE `chyba` SET `errors` = '".$count."', date = NOW() WHERE `ip`='".$ip."'");
			} else {
// V případě, že uživatel ještě nechyboval přidame nový záznam
				mysql_query("INSERT INTO `chyba` (`ip`,`date`,`errors`) VALUES('".$ip."',NOW(),'1')");
			}
			
			exit("Je nám líto, zadali jste chybné uživatelské jméno nebo heslo");
		} else {
// V připadě, že uživatelské jméno a heslo odpovídají ( jsou v databázi ),
// Vytvořime uživatelsou session.			
			$_SESSION['heslo'] = $r1['pass']; 
            $_SESSION['login'] = $r1['login']; 
			$_SESSION['id'] = $r1['id'];

// Dále uložime data do COOKIE, pro pozdější přihlášení 	           
// POZOR!!! DATA V COOKIE NEJSOU ŠIFROVÁNÁ, DĚLEJTE TO NA ZÁKLADĚ VLASTNÍHO UVÁŽENÍ			
      if (isset($_POST['save']))
      {
		    if($_POST['save'] == 1) 
			{
// Jestli uživetel chce, aby jeho data byla uložena pro pozdější přihlášení, pak uložime je do COOKIE souboru prohlížeče 
				setcookie("login", $_POST['login'], time()+60*60*24); //24hodin zustane cookie
				setcookie("password", $_POST['heslo'], time()+60*60*24);
				setcookie("id", $r1['id'],    time()+60*60*24);
			}   
      }
			
			if(isset($_POST['autovstup']))
			{
// Pokud se uživatel chce přihlášovat automatický 				
				setcookie("auto", "yes", time()+60*60*2); //2hodiny zustane cookie
				setcookie("login", $_POST['login'], time()+60*60*2);
				setcookie("password", $_POST['heslo'], time()+60*60*2);
				setcookie("id", $r1['id'], time()+60*60*2);
            }                
            
            header("Location: ".$_SERVER['SERVER_ROOT']."map.php");
		}
	}

//--------------KONEC NOVÉHO------------------------------------------//

	

} else { header("Location: ".$_SERVER['SERVER_ROOT']."index.php"); }
?>
