<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

  <title>Company rules</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" >

  <link rel="stylesheet" href="css/css/kraken.css" />
<link rel="stylesheet" href="css/map.css" />
<link rel="shortcut icon" href="img/sviti.png" />
<style>
.content_container{
background:rgb(218, 160, 85);
}
 .cell {
      text-align: left;
    padding-right: 20px;
   }
   .row{
    background:lightgray;
    color:black;
      display: table-row-group;
   }
   .frist_table_row{
     background:white;
     color:black;
   }
   .row:hover {
  background: #FFE4C2;
  color:black;
}
.table_row_odd {
  background: #E0B276;
}
.table_row_even {
  background: #F3D2A7;
}
.caption{
font-weight:bold;
text-align: left;
  display: inline;
}
.frist_cell {
  padding-left: 5px;
}
.rule_table{
    border-collapse: collapse;
      }
input[type="checkbox"]{

margin-left: 45%;
  margin-right: 45%;
  }
.buttons:hover{
background:rgb(201, 159, 106);
}
</style>
</head>
<body>
<div class="container">
<div class="space50">
</div>
<div class="menu_container">';


require_once 'module/usermenu.php';
require_once 'module/menu.php';
echo '</div>';

$rcompany = mysqli_query($dataconection, "SELECT  rule_access.users_id,rule_access.companyadmin AS is_admin,rule_access.is_member AS is_member,company.ID_company AS ID_company,company.company_name AS company_name, company.company_display_name AS company_display_name,company.logo_link AS logolink ,licensetype.License_type FROM `rule_access`
LEFT OUTER JOIN company ON company.ID_company = rule_access.company_ID_company
LEFT OUTER JOIN license_managment ON rule_access.users_id = license_managment.users_id
LEFT OUTER JOIN licensetype ON licensetype.License_type = license_managment.licensetype_license_type
WHERE rule_access.users_id = " . $_SESSION['id'] . " AND rule_access.company_ID_company = " . $_SESSION['company'] . ";");
$actualmember = mysqli_fetch_array($rcompany, MYSQLI_ASSOC);

//SELECT * FROM `users`
//LEFT OUTER JOIN rule_access AS Rule ON users.id = Rule.users_ID
//WHERE Rule.is_member=1 AND Rule.company_ID_company=0;

echo 'Jméno společnosti:<br>'.$actualmember['company_display_name'].'<br>';
if($actualmember['is_member']) {
    $completcompany = mysqli_query($dataconection, "SELECT  rule_access.users_id,users.name as username,rule_access.companyadmin AS is_admin,rule_access.is_member AS is_member,company.ID_company AS ID_company,company.company_name AS company_name, company.company_display_name AS company_display_name,company.logo_link AS logolink ,license_managment.licensetype_License_type FROM `rule_access`
LEFT OUTER JOIN company ON rule_access.company_ID_company = company.ID_company
LEFT OUTER JOIN license_managment ON rule_access.company_ID_company = license_managment.company_ID_company
LEFT OUTER JOIN licensetype ON licensetype.License_type = license_managment.licensetype_license_type
LEFT OUTER JOIN users ON users.id = rule_access.users_id
WHERE rule_access.is_member = 1 AND rule_access.company_ID_company = " . $_SESSION['company'] . ";");
        //prava vypis
    echo '<br>Členové společnosti:<br>';
    while($companymember = mysqli_fetch_array($completcompany, MYSQLI_ASSOC)) {
        if ($actualmember['users_id']==$companymember['users_id']) {
            echo $companymember['username'] . '(odebrat ze spolecnosti)(editovat profil)<br>';
        }else{
            echo $companymember['username'] . '(profil uzivatele)<br>';
        }
    }

    echo "<br>Vypis spolecnosti";
}else{
    //pridani ke spolecnosti
    if($actualmember['is_member']==0){
     echo "<br>Čekejte na potvrzení od admina společnosti...";
    }else {
        if (isset($_POST['add_user_to_company'])) {
            mysqli_query($dataconection, "INSERT INTO rule_access (`Super_admin`, `View_lamp`, `Edit_lamp`, `Edit_rule`, `Edit_company`, `Set_role`, `company_ID_company`, `users_id`, `companyadmin`, `is_member`) VALUES ('0', '0', '0', '0', '0', '0', '" . $_SESSION['company'] . "',  '" . $_SESSION['id'] . "', '0', '0');");
        }
    }
}