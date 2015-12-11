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

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

if (isset($_POST['rename'])) {
    //echo "<br>";
    //echo $_POST['name'];
    //echo "<br>";
    //echo slugify($_POST['name']);
    $sql = "UPDATE company SET `company_name`='" . slugify($_POST['name']) . "', `company_display_name`='" . $_POST['name'] . "' WHERE `ID_company`='" . $_SESSION['company'] . "';";
    mysqli_query($dataconection, $sql);
    //echo mysqli_affected_rows($dataconection);

}
if (isset($_POST['changelogo'])) {
    $target_dir = "img/logos/";
    $target_base_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_base_file,PATHINFO_EXTENSION);
    $target_file = $target_dir . basename($_SESSION['company']).".".$imageFileType;
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "<script>console.log('File is an image - " . $check["mime"] . ".');</script>";
        $uploadOk = 1;
    } else {
        echo "<script>console.log('File is not an image.');</script>";
        $uploadOk = 0;
    }

    //if (file_exists($target_file)) {
    //    echo "Sorry, file already exists.";
    //    $uploadOk = 0;
    //}
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "<script>console.log('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo "<script>console.log('Sorry, your file was not uploaded.');</script>";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>console.log('The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded as ".$target_file."');</script>";
        } else {
            echo "<script>console.log('Sorry, there was an error uploading your file.');</script>";
        }
    }


    //echo "<br>";
    //echo $target_file;
    $sql = "UPDATE company SET `logo_link`='" . $target_file . "' WHERE `ID_company`='" . $_SESSION['company'] . "';";
    mysqli_query($dataconection, $sql);
    //echo "<br>";
    //echo mysqli_affected_rows($dataconection);
    //echo "<br>";
}


echo '<div style="text-align: center;"><div class="companyprofile">';
if ($_SESSION['id']) {
    $result = mysqli_query($dataconection, "SELECT *
FROM  `users`
LEFT OUTER JOIN rule_access AS Rule ON users.id = Rule.users_ID
WHERE users.id=" . $_SESSION['id'] . " AND Rule.company_ID_company=".$DEMOCOMPANYID.";");


    $row = mysqli_fetch_array($result);
    extract($row);
    if ($Super_admin != 1) {
        $resultcompany = mysqli_query($dataconection, "SELECT  rule_access.companyadmin AS is_admin,rule_access.is_member AS is_member,licensetype.License_type,company.ID_company AS ID_company,company.company_name AS company_name, company.company_display_name AS company_display_name,company.logo_link AS logolink FROM `users`
LEFT OUTER JOIN license_managment ON users.id = license_managment.users_id
LEFT OUTER JOIN company ON company.ID_company = license_managment.company_ID_company
LEFT OUTER JOIN licensetype ON licensetype.License_type = license_managment.licensetype_license_type
LEFT OUTER JOIN rule_access ON rule_access.company_ID_company = company.ID_company
WHERE company.ID_company=" . $_SESSION['company'] . ";
");
        echo 'company: ' . $_SESSION['company'] . '<br>';
        $row = mysqli_fetch_array($resultcompany, MYSQLI_ASSOC);
        echo "Admin: " . $row['is_admin'];
        echo '<br>';
        if (isset($row['is_admin'])) {
            if ($row['is_admin'] == 1) {
                $companyadmin = True;
            }
        }


    } else {

        echo "Jsem super";
        $companyadmin = True;
    }
}

if ($companyadmin) {
    echo "Jsem admin";
    $inputcompanyname = '
<form action="" method="POST">Jméno společnosti:
  <input type="text" name="name" value="' . $row['company_display_name'] . '">
  <input class="buttons" type="submit" name="rename" value="Změnit název">
</form>
<form action="" method="POST" enctype="multipart/form-data">    Logo:
  <input type="file" name="fileToUpload" id="fileToUpload" value="'.$row['logolink']. '">
  <input class="buttons" type="submit" value="Změnit logo" name="changelogo">
</form>
';
    require_once "pravidla.php";
} else {
    require_once "module/member_profile.php";
}

echo "<br>";

echo '</div></div>';
?>