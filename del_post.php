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

// Uložíme identifikator zprávy do proměnné $id				
        if (isset($_GET['id']) AND $_GET['id'] != '') {
            $id = stripslashes(htmlspecialchars(trim($_GET['id'])));

// Vybereme z DB příjemce zprávy podle id	
            $query = mysqli_query($dataconection, "SELECT `prijemce` FROM `message` WHERE `id`='" . $id . "'");
            if (mysqli_num_rows($query) != 0) {
                $result = mysqli_fetch_assoc($query);

// Ověříme, zda se jedna o správného příjemce zprávy, protože do GET proměnné v URL můžete zadát libovolný identifikátor, tím pádem odstranit cizí zprávu
                if ($result['prijemce'] == $login) {
// V případě, že příjemce odpovídá přihlášovacímu jménu, odstraníme zprávu
                    $query1 = mysqli_query($dataconection, "DELETE FROM `message` WHERE `id`='" . $id . "'");
                    if (!$query1) {
                        echo "<script>alert('Zpráva nebyla smazána.')</script>";
                        header("Refresh: 0; url=uzivatel.php?id=" . $_SESSION['id'] . "");
                    } else {
                        echo "<script>alert('Zpráva byla smazána.')</script>";
                        header("Refresh: 0; url=uzivatel.php?id=" . $_SESSION['id'] . "");
                    }
                } else {
                    exit("Chcete odstranít cizí zprávu.");
                }
            } else {
                exit("Zpráva s tímto identifikátorem neexistuje.");
            }
        } else {
            exit("Jste tady asi omylem.");
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
