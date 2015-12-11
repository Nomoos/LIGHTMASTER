<?php
session_start();
require_once "variables.php"

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Registrace</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<?php
echo '
    <link rel="stylesheet" href="'.$_SERVER['SERVER_ROOT'].'css/css/kraken.css">';
?>
    <style>
        .container {
            width: 20%;
            margin-left: auto;
            margin-right: auto;
        }

        .back {
            font-size: 10pt;
            margin-top: 20px;
        }

        .headcontainer{
            margin-left: auto;
            margin-right: auto;
            width: 550px;
        }
        .flag {
            width: 30px;
        }
        .flags{
            float: right;
        }
        .description{
            margin-left: 50px;
        }
        .projectname{
            font-weight: bold ;
        }
        .registrationfail{
            color:red;
            font-weight: bold;
        }

    </style>

</head>

<body>
<?php
echo '
<div class="headcontainer">
    <img class="logolight" src="'.$_SERVER['SERVER_ROOT'].'img/logolight.png" alt="Lightmaster logo"><div class="flags"><div class="projectname">Projekt: ME1 – C2M</div><img class="flag" src="'.$_SERVER['SERVER_ROOT'].'img/countryflags/cz.png" alt="Czech flag"><img class="flag" src="'.$_SERVER['SERVER_ROOT'].'img/countryflags/en.png" alt="English flag"></div>
    <div class="description">Řídící a monitorovací systém pro pouliční LED osvětlení třídy ME1.</div>
</div>
';
?>
<div class="container">
    <?php
    if(isset($_SESSION['registrationfail'])) {
        echo '<div class="registrationfail">' . $_SESSION['registrationfail'] . '</div>';
        unset($_SESSION['registrationfail']);
    }
    ?>
    <h1>Registrace<br>nového uživatele</h1>

    <form action="adduser.php" method="POST" enctype="multipart/form-data">
        <!--  pridavani.php je skript pro přidávaní nových uživatelů do databáze, tzn. po klíknutí na tlačítko Ok se data pošlou do tohoto souboru metodou $_POST  -->

        <div><label for="name">Jméno:</label></div>
        <div><input type="text" name="name"></div>
        <!--  Do textového pole se jménem name ( name="name" ) nový uživatel zadá svoje jméno  -->

        <div><label for="login">Login:</label></div>
        <div><input type="text" name="login"></div>
        <!--  Do textového pole se jménem login ( name="login" ) nový uživatel zadá svůj login  -->


        <!-----------------NOVÉ------------------------->


        <div><label for="email">Váš E-mail:</label></div>
        <div><input name="email" type="text"></div>
        <!--  Do textového pole se jménem email ( name="email" ) nový uživatel zadá svůj platný email  -->


        <!-----------------KONEC NOVÉHO------------------------->
        <!--

            <div><label for="avatar">Avatar. Obrázek musi být ve formatu jpg, gif nebo png:</label></div>
            <div><input type="FILE" name="avatar"></div>
          -->
        <!--  do proměnné avatar se uloži obrázek, který vybral uživatel  -->

        <div><label for="heslo">Heslo:</label></div>
        <div><input type="password" name="heslo"></div>
        <!--  Do pole se jménem heslo ( name="heslo" ) nový uživatel zadá svoje heslo  -->


        <div><label for="keystring">Opište čisla a písmena z obrazku:</label></div>

        <div><img src="kcaptcha/img.php" id="image"></div>

        <div><a href="#captcha"
                onClick="document.getElementById('image').src='kcaptcha/img.php?rand='+Math.round(1000 * Math.random());">Refresh</a>
        </div>

        <div><input type="text" name="keystring"></div>
        <!--  Do textového pole se jménem keystring ( name="keystring" ) uživatel opiše čisla a písmena z obrazku  -->


        <div><input type="submit" name="go" value="Ok"></div>
        <!--  tlačítko ( type="submit" ) odesíla data do souboru pridavani.php  -->

    </form>

    <div class="back"><a href="index.php">přihlasit se</a></div>
</div>
</body>
</html>

