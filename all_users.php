<?php
// Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky! 
session_start();
header('Content-type: text/html;charset=UTF-8');

// Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
require_once 'db.php';

if (!empty($_SESSION['login']) AND !empty($_SESSION['heslo'])) {
// Dál ověřime, zda jsou tyto údaje platné
    $login = $_SESSION['login'];
    $heslo = $_SESSION['heslo'];
    $over = mysqli_query($dataconection, "SELECT `id` FROM `users` WHERE `login`='" . $login . "' AND `pass`='" . $heslo . "'");

// Pokud najdeme identifikator s tímto loginem a heslem uložime ho do pole $res_over
    if (mysqli_num_rows($over) != 0) {
        $res_over = mysqli_fetch_assoc($over);
    } else {
// Jinak zobrazíme chybu
        exit("Vstup na tuto stránku je povolen pouze přihlášeným uživatelům.");
    }

} else {
// Pokud proměnné neexistují zobrazíme chybu
    exit("Vstup na tuto stránku je povolen pouze přihlášeným uživatelům.");
}
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Seznam uživatelů</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" >
</head>
<body>

<h1>Seznam uživatelů</h1>';

// Navigace
echo '<div><a href="uzivatel.php?id=' . $res_over['id'] . '">Můj profil</a>
| <a href="index.php">Hlavní stránka</a> 
| <a href="all_users.php">Seznam uživatelů</a> 
| <a href="index.php?action=odhlasit_se">Odhlásit se</a></div>';

$query = mysqli_query($dataconection, "SELECT `id`,`login` FROM `users` ORDER BY `login` ASC");
if (!$query) {
    echo mysqli_error($dataconection) . " - " . mysqli_errno($dataconection);
} else {
    while ($result = mysqli_fetch_assoc($query)) {
        echo "<div><a href=\"uzivatel.php?id=" . $result['id'] . "\">" . $result['login'] . "</a></div>";
    }
}

echo '
</body>
</html>';
?>
