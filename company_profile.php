<?php
/**
 * Created by PhpStorm.
 * User: Hittl Roman
 * Date: 21.11.2015
 * Time: 11:34
 */


function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text))
    {
        return 'n-a';
    }

    return $text;
}

if (isset($_POST['rename'])) {
    echo "<br>";
    echo $_POST['name'];
    echo "<br>";
    echo slugify($_POST['name']);
    $sql = "UPDATE company SET `company_name`='".slugify($_POST['name'])."', `company_display_name`='".$_POST['name']."' WHERE `ID_company`='" . $_SESSION['company'] . "';";
    mysqli_query($dataconection, $sql);
    echo mysqli_affected_rows($dataconection);
    echo "<br>";
}


echo '<div style="text-align: center;"><div class="companyprofile">';
if ($_SESSION['id']) {
    $result = mysqli_query($dataconection, "SELECT *
FROM  `users`
LEFT OUTER JOIN rule_access AS Rule ON users.id = Rule.users_ID
WHERE users.id=" . $_SESSION['id'] . ";");


    $row = mysqli_fetch_array($result);
    extract($row);
    if ($Super_admin != 1) {
        $resultcompany = mysqli_query($dataconection, "SELECT  rule_access.companyadmin AS is_admin,licensetype.License_type,company.ID_company AS ID_company,company.company_name AS company_name, company.company_display_name AS company_display_name FROM `users`
LEFT OUTER JOIN license_managment ON users.id = license_managment.users_id
LEFT OUTER JOIN company ON company.ID_company = license_managment.company_ID_company
LEFT OUTER JOIN licensetype ON licensetype.License_type = license_managment.licensetype_license_type
LEFT OUTER JOIN rule_access ON rule_access.company_ID_company = company.ID_company
WHERE company.ID_company=" . $_SESSION['company'] . ";
");
        echo 'company: '.$_SESSION['company'].'<br>';
        $row = mysqli_fetch_array($resultcompany, MYSQLI_ASSOC);
        echo "Admin: ".$row['is_admin'];
        echo '<br>';
        if(isset($row['is_admin'])) {
            if ($row['is_admin'] == 1) {
                $companyadmin = True;
            }
        }


    }else{

        echo "Jsem super";
        $companyadmin = True;
    }
}

if($companyadmin){
echo "Jsem admin";
$inputcompanyname = '<form action="" method="POST">Jméno společnosti:<input type="text" name="name" value="'.$row['company_display_name'].'"><input class="buttons" type="submit" name="rename" value="Změnit název"></form>';
require_once "pravidla.php";
}else{

    echo "Nejsem admin";
}

echo "<br>";

echo '</div></div>';
?>