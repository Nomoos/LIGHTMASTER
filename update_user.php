<?php
// Celý postup funguje na sessions. Právě v session se ukládají data uživatele, zatímco se nacházi na stránkach. Je důležite spustit sessions na začátku stránky! 
session_start();
header('Content-type: text/html;charset=UTF-8');

// Vložime potřebné funkce
require_once 'function.inc.php';

// Vložíme soubor s připojením k databázi. ( musí se nacházet ve stejné složce )	
require_once 'db.php';

// Ověřme, zda existujou proměnné $_SESSION['login'] a $_SESSION['heslo'].
if (!empty($_SESSION['login']) AND !empty($_SESSION['heslo'])) {
// Dál ověřime, zda jsou tyto údaje platné
    $login = $_SESSION['login'];
    $heslo = $_SESSION['heslo'];
    $over = mysqli_query($dataconection, "SELECT `id` FROM `users` WHERE `login`='" . $login . "' AND `pass`='" . $heslo . "'");

// Pokud najdeme identifikator s tímto loginem a heslem uložime ho do pole $res_over
    if (mysqli_num_rows($over) != 0) {
        $res_over = mysqli_fetch_assoc($over);

// Do proměnné $old_login uložíme stávající přihlášovací jméno
        $old_login = $_SESSION['login'];

// Taktéž uložíme identifikator uživatele, který můžeme získat z session a jako $res_over['id']				
        $id = $_SESSION['id'];

// Pro změnu avataru uložíme defaultní obrázek do proměnné $def_ava
        $def_ava = 'no_avatar.jpg';

        /****************ZMĚNA LOGINA**************************/


// Pokud je vyplněno pole login
        if (isset($_POST['login']) AND $_POST['login'] != '') {
// ošetříme ho a uložíme do proměnné $login
            $login = stripslashes(htmlspecialchars(trim($_POST['login'])));
// Ověřime, zda se jíž nepoužíva
            $query = mysqli_query($dataconection, "SELECT `id` FROM `users` WHERE `login`='" . $login . "'");
// Pokud je toto jméno v databází
            if (mysqli_num_rows($query) != 0) {
// Zobrazíme hlášení
                exit("Toto uživatelské jméno je jíž v databázi, zkuste jiné.");
            } else {

// Pokud není tak aktualizujeme tabulku users a nastavíme login se rovná nový login
                $query1 = mysqli_query($dataconection, "UPDATE `users` SET `login`='" . $login . "' WHERE `login`='" . $old_login . "'");
                if (!$query1) {
                    echo mysqli_error($dataconection) . " - " . mysqli_errno($dataconection);
                } else {

// Jestli změna proběhla vpořádku taktéž musíme aktualizovat tabulku se zprávami, kde aktualizujeme jméno odesílatele
                    $query2 = mysqli_query($dataconection, "UPDATE `message` SET `odesilatel`='" . $login . "' WHERE `odesilatel`='" . $old_login . "'");
// Uložíme nové přihlášovací jméno do sessions
                    $_SESSION['login'] = $login;

                    /***************NOVÉ*********************/

// Aktualizujeme login v COOKIE proměnné, pokud je. 
                    if (isset($_COOKIE['login'])) {
                        setcookie("login", $login, time() + 9999999);
                    }

                    /***************KONEC NOVÉHO*********************/

                    echo "<script>alert('Změna proběhla vpořádku.')</script>";
                    header("Refresh: 0; url=uzivatel.php?id=" . $_SESSION['id'] . "");
                }
            }
        }


        /****************KONEC ZMĚNA LOGINA**************************/


        /****************ZMĚNA HESLA**************************/

// Pokud je vyplněno pole pro změnu hesla 
        elseif (isset($_POST['heslo']) AND $_POST['heslo'] != '') {
// ošetříme ho a uložíme do proměnné $heslo
            $heslo = stripslashes(htmlspecialchars(trim($_POST['heslo'])));
// Zašifrujeme heslo pomocí algoritmu md5       
            $heslo = md5($heslo);
// Ještě přidáme reverzí       
            $heslo = strrev($heslo);

// můžete přidat několik sybmolů, např. "g5ds8". V případě, že takto ošetřené heslo někdo bude chtít prolomit Brute Forcem ( hádání hesla )
// a použijí k tomu md5, pak se níčeho nedocíli. Můžete taky přidat symboly na začátku nebo uprostřed hesla.          
            $heslo = $heslo . "g5ds8";

// Aktualizujeme tabulku users					
            $query3 = mysqli_query($dataconection, "UPDATE `users` SET `pass`='" . $heslo . "' WHERE `login`='" . $login . "'");
            if (!$query3) {
                echo mysqli_error($dataconection) . " - " . mysqli_errno($dataconection);
            } else {
// Uložíme nové heslo do sessions
                $_SESSION['heslo'] = $heslo;


                /***************NOVÉ*********************/

// Aktualizujeme heslo v COOKIE proměnné, pokud je. 
                if (isset($_COOKIE['password'])) {
                    setcookie("password", $_POST['heslo'], time() + 9999999);
                }

                /***************KONEC NOVÉHO*********************/


                echo "<script>alert('Změna proběhla vpořádku.')</script>";
                header("Refresh: 0; url=uzivatel.php?id=" . $_SESSION['id'] . "");
            }
        }


        /****************KONEC ZMĚNA HESLA**************************/


        /****************ZMĚNA OBRÁZKU**************************/

// Pokud je vyplněno pole pro změnu obrázku
        elseif (isset($_FILES['avatar']['name'])) {

// Jestli je prázdné, tak přiřadíme mu náš defaultí obrázek no_avatar.jpg
            if (empty($_FILES['avatar']['name']) OR $_FILES['avatar']['name'] == '') {
                $avatar = 'no_avatar.jpg';

// Vybereme stávající obrázek uživatele
                $query4 = mysqli_query($dataconection, "SELECT `avatar` FROM `users` WHERE `login`='" . $login . "'");
                $result4 = mysqli_fetch_assoc($query4);

// Pokud je obrázek defaultní tak ho nechceme odstranít, protože je jeden pro všechny 						
                if ($result4['avatar'] == $def_ava) {
                    $$def_ava = 1;
                } else {
// Když není defaultní tak ho odstraníme	
                    unlink("avatar/" . $result4['avatar']);
                }

// Jinak, jestli byl zadán nový obrázek
            } else {

// Použijeme naši funkci upload_file() a uložíme nový avatar do proměnné $avatar				
                $avatar = upload_file();

// Pokud je i po použití funkce proměnná $avatar prázdná, tak do ní uložíme defaultní hodnotu	
// Toto se může stát na lokálním serveru, třeba u mě se neukládájí soubory na localhost					
                if ($avatar == '') {
                    $avatar = 'no_avatar.jpg';
                } else {
// Uděláme z původního obrázku, obrázek o velikosti 90x90
                    $mini_av = resizeimg("avatar/$avatar", "avatar/mini-$avatar", 90, 90);

// Odstraníme hned původní obrázek
                    unlink("avatar/" . $avatar);
                    $avatar = 'mini-' . $avatar;
                }

                /*** Odstraníme starý obrázek    ***/

// Vybereme stávající obrázek uživatele
                $query5 = mysqli_query($dataconection, "SELECT `avatar` FROM `users` WHERE `login`='" . $login . "'");
                $result5 = mysqli_fetch_assoc($query5);

// Pokud je obrázek defaultní tak ho nechceme odstranít, protože je jeden pro všechny 						
                if ($result5['avatar'] == $def_ava) {
                    $def_ava = 1;
                } else {
// Když není defaultní tak ho odstraníme	
                    unlink("avatar/" . $result5['avatar']);
                }

                /*** KONEC Odstraníme starý obrázek    ***/

// Aktualizujeme tabulku users							
                $query6 = mysqli_query($dataconection, "UPDATE `users` SET `avatar`='" . $avatar . "' WHERE `login`='" . $login . "'");
                if (!$query6) {
                    echo mysqli_error($dataconection) . " - " . mysqli_errno($dataconection);
                } else {
                    echo "<script>alert('Změna proběhla vpořádku.')</script>";
                    header("Refresh: 0; url=uzivatel.php?id=" . $_SESSION['id'] . "");
                }
            }
        } /****************KONEC ZMĚNA OBRÁZKU**************************/
        else {
            echo "Zadejte parametr, který chcete změnit!!!";
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
