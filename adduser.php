<?php
session_start(); /**********NEZAPOMENOUT*****************/
header('Content-type: text/html;charset=UTF-8');

// Vložime potřebné funkce
require_once 'function.inc.php';

	
if(isset($_POST['go'])){	//jestli tlačítko bylo zmačknuté tak jdi dál, jinak budete přesměrování na stránku pro registraci nového uživatele


// vytvořime proměnnou, do které uložime hodnotu z captchi ( to co vidíme na obrázku )  
$keystring = $_REQUEST['keystring'];


	if($keystring != $_SESSION['captcha_keystring'] OR empty($keystring)) 
    {        
		unset($_SESSION['captcha_keystring']);
        exit("Špatně jste opsali písmena a čísla z obrazku!");                     
    }
// V sessions se nám vytvoří proměnná captcha_keystring, které se přiřadí určita hodnota ( to co vidíme na obrázku )  
// ověřime, zda je stejná jako v proměnné $keystring a jestli není prázdna.



	if(isset($_POST['name']))
	{
		$name = stripcslashes(htmlspecialchars(trim($_POST['name'])));
		if($name == '')
		{
				unset($name);
		}
	}
//uložime do proměnné $name jméno, které uživatel zadál ve formuláři, 
//a hned ji ošetříme několika funkcemi, aby ani tagy ani skripty nefungovali a odstraníme mezery ( trim ) 
//Jestli je proměnná prázdna tak ji odstraníme.

	
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


/***************NOVÉ*********************/


	if(isset($_POST['email']))
	{
		$email = stripcslashes(htmlspecialchars(trim($_POST['email'])));
		if($email == '')
		{
				unset($email);
		}
	}
//uložime do proměnné $email email, který uživatel zadál ve formuláři, 
//a hned ji ošetříme několika funkcemi, aby ani tagy ani skripty nefungovali a odstraníme mezery ( trim ) 
//Jestli je proměnná prázdna tak ji odstraníme.



// ověříme email regulárním výrazem, protože potřebujeme platný email pro aktivací účtu
	if(!preg_match("/^[a-z0-9_-]{1,20}+(\.){0,20}+([a-z0-9_-]){0,20}@(([a-z0-9-]+\.)+(com|net|org|mil|".
	"edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-".
	"9]{1,3}\.[0-9]{1,3})$/is",$email)) 
	{
		exit    ("E-mail není platný!");
	}


/***************KONEC NOVÉHO*********************/



//V případě, že jakákoli proměnná je prázdna, zastavíme skript a vyvedeme hlášení	
	if(empty($name) or empty($login) or empty($heslo) or empty($heslo))
	{
		exit("Vyplňte všechna pole");
	}



// ověřujeme, zda uživatel vložil obrázek
if(!empty($_FILES['avatar']['name']))
{
// Uložime obrázek ( název ) do proměnné avatar a ošetřime ji
   $avatar = upload_file();
// Jestli se proměnná rovna 0 nebo je prázdna  
   if($avatar == '' OR empty($avatar)) 
   {
// V případě, že proměnná je prázdna odstraníme ji
      unset($avatar);
   }
}



if(!isset($avatar) OR empty($avatar) OR $avatar == '')
{
// V případě, že proměnná neexistuje, automatický přiřádime uživateli obrázek s nadpisem "Nemá avatar"
// nakreslete obrázek no_avatar.jpg nebo stáhněte jej z archivu k článku
/*
    $avatar = "avatar/no_avatar.jpg"; 
*/
	$avatar = "no_avatar.jpg";
} else {
// Uděláme z původního obrázku, obrázek o velikosti 90x90	
	$mini_av = resizeimg("avatar/$avatar","avatar/mini-$avatar",90,90); 	
	$avatar = 'mini-'.$avatar;
}   

// Zašifrujeme heslo pomocí algoritmu md5       
$heslo = md5($heslo); 
// Ještě přidáme reverzí       
$heslo = strrev($heslo); 

// můžete přidat několik sybmolů, např. "g5ds8". V případě, že takto ošetřené heslo někdo bude chtít prolomit Brute Forcem ( hádání hesla )
// a použijí k tomu md5, pak se ničeho nedocíli. Můžete taky přidat symboly na začátku nebo uprostřed hesla.          
$heslo = $heslo."g5ds8";


// Nezapomeňte přidat pole `avatar` do SQL příkazu pro vkládáni dat do databáze  



//Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
	require_once 'db.php';

//Ověřujeme, zda jíž není uživatel se stejným loginem	
	$q1 = mysqli_query($dataconection, "SELECT * FROM `users` WHERE `login`='".$login."'");
	if(!$q1) { echo mysqli_error($dataconection) . ' - ' . mysqli_errno($dataconection); }
	else {
//Jestli existuje tak vyvedeme hlášení
		if(mysqli_num_rows($q1)==1){
			exit("Uživatelské jméno je obsazené, vyberte si jiné");
		} else {
			
			
/***************NOVÉ*********************/
			
			
//V případě, že není tak vložíme data o novém uživateli do databáze
// !!! NEZAPOMEŇTE VLOŽIT DO DATABÁZE EMAIL A DATUM !!!
			$q2 = mysqli_query($dataconection, "INSERT INTO `users`(`name`,`login`,`pass`,`avatar`,`email`,`date`) VALUES('".$name."','".$login."','".$heslo."','".$avatar."','".$email."', NOW())");
			if(!$q2) { echo mysqli_error($dataconection) . ' - ' . mysqli_errno($dataconection); }
			else {
				
// vybereme identifikátor nového uživatele a pomocí něj vytvoříme kód aktivace účtu
				$q3 = mysqli_query($dataconection, "SELECT `id` FROM `users` WHERE `login`='".$login."'");
				$r3 = mysqli_fetch_assoc($q3);
        
				mysqli_query($dataconection, "INSERT INTO `Rule_access` (`Rule_access_ID`, `Super_admin`, `View_lamp`, `Edit_lamp`, `Edit_rule`, `Edit_company`, `ID_user`, `ID_company`, `Set_role`, `x_Modify`) VALUES (NULL, '0', '1', '0', '0', '0', '".$r3['id']."', '', '0', NOW());");
        
// do proměnné $activation zašifrujeme identifikátor a přihlášovací jméno
				$activation = md5($r3['id']).md5($login);
				
// pošleme uživateli e-mail s aktivačním kódem
				$_to = $email;
				
				$_subject = "Potvrzení registrace";
				
				$_message = "<div style=\"font-size:11pt; font-family:Times New Roman; color:black; padding:5px;\"><div>Dobrý den,</div>";
				$_message .= "<div style=\"text-indent:10px;\">děkujeme Vám za registraci na portálu Lightmaster.cz. Pro aktivaci vašeho účtu klikněte na odkaz:</div>";
				$_message .= "<div style=\"text-indent:10px;\">http://lamps.lightmaster.cz/www2/activation.php?login=".$login."&code=".$activation.".</div>";
        $_message .= "<div style=\"margin:10px 0;\">Vaše uživatelské jméno: ".$login."</div>";
				$_message .= "<div>S pozdravem, administrace Lightmaster.cz</div>";
				
				$_headers  = 'MIME-Version: 1.0' . "\r\n";
				$_headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$_headers .= 'From: <info@lightmaster.cz>' . "\r\n";
	
				if(@mail($_to, '=?UTF-8?B?'.base64_encode($_subject).'?=', $_message, $_headers))
				{
					echo "Za chvíli obdržíte e-mailovou zprávu s odkazem pro potvrzení registraci. Pozor! Odkaz je platný 1 hodinu. <a href=\"index.php\">Hlavní stránka</a>";
				} else {
					echo "E-mail nebyl odeslán. Zkuste to za 5 minut.";
				}
			
			}
			
			
/***************KONEC NOVÉHO*********************/


		}
	}
	
} else { header("Location: ".$_SERVER['SERVER_ROOT']."registration.php"); }
?>
