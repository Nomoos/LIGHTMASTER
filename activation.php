<?php
header('Content-type: text/html;charset=UTF-8'); 

//Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )		
require_once 'db.php';

if(isset($_GET['login']) AND isset($_GET['code']))
{	
	$login = stripslashes(htmlspecialchars(trim($_GET['login'])));
	$code = htmlspecialchars(trim($_GET['code']));
	
// Vybereme identifikátor uživatele abysme vytvořili aktivační kód
	$query = mysql_query("SELECT `id` FROM `users` WHERE `login`='".$login."'");
	if(mysql_num_rows($query)!=0)
	{
		$result = mysql_fetch_assoc($query);
		$activation = md5($result['id']).md5($login);
		
// Porovnáme aktivační kód, který uživatel odesíla kliknutím na odkaz v e-mailu s nově vytvořeným
		if($activation == $code)
		{
			
// Pokud tyto kódy odpovídají, aktualizujeme tabulku users a nastavíme pole activation na 1. ( 1 - uživatel aktivován, 0 - uživatel není aktivován )
			$query1 = mysql_query("UPDATE `users` SET `activation`=1 WHERE `login`='".$login."'");
			if(!$query1) { echo mysql_error() . " - " . mysql_errno(); }
			else {
				echo "Váš e-mail byl úspěšně potvrzen, teď se můžete <a href=\"index.php\">přihlásit</a>.";
			}
		} else {
			echo "Váš e-mail nebyl potvrzen <a href=\"index.php\">Hlavní stránka</a>";
		}
	} else {
		exit("Login a aktivační kód neodpovídají");
	}
} else {
	exit("Nemáte co tady dělat.");
}

// Ještě odstraníme uživatele, kteři neaktivovali e-mail do 1 hodiny
// Vybereme jejich obrázky, abysme je následně odstranili.
$query2 = mysql_query("SELECT `avatar` FROM `users` WHERE `activation`=0 AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 3600");
if(mysql_num_rows($query2) > 0)
{
// projdeme cyklusem všechny záznamy
	while($result2 = mysql_fetch_assoc($query2))
	{
// pokud nemají vlastní obrázek, tak nic nebudeme dělat
			if($result2['avatar'] == 'no_avatar.jpg')
			{
				$nic = '';
			} else {
// Jinak odstraníme obrázek
				unlink("avatar/".$result2['avatar']);
			}
	}
// Odstraníme záznamy z databáze
	mysql_query("DELETE FROM `users` WHERE `activation`=0 AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 3600");
} 
?>
