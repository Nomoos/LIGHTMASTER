<?php

$result = mysql_query("SELECT * 
FROM  `users` 
LEFT OUTER JOIN Rule_access AS Rule ON users.id = Rule.ID_user
WHERE users.id=".$_SESSION['id'].";");


echo '
  <div style="usermenu">';

$row = mysql_fetch_array($result);
extract($row);
if($Super_admin==1)
{
$result = mysql_query("SELECT ID_company, Company_name
FROM  `Company` ");
}else{
$result = mysql_query("SELECT Company.ID_company AS ID_company,Company.Company_name AS Company_name FROM `users`
LEFT OUTER JOIN License_managment AS Managment ON users.id = Managment.ID_user
LEFT OUTER JOIN Company ON Company.ID_company = Managment.ID_company
WHERE users.id=".$_SESSION['id']."; 
");
}

?>
<script>
var select_company;
</script>
<select id="company" name="company" onchange='select_company=document.getElementById("company").value;change_company();'>
<?php
$_SESSION['company']=array();
While( $row = mysql_fetch_array($result) )
{
extract($row);

      $_SESSION['company'][$ID_company]=$Company_name;      
      echo '<option value="'.$ID_company.'">'.$Company_name.'</option>';

}
 
?>
    </select>
<?php


  
  echo '<script>document.getElementById("company").value;</script>
   <a href="pravidla.php">Přistupová pravidla</a> 
|  <a href="uzivatel.php?id='.$_SESSION['id'].'">Můj profil</a> 
| <a href="index.php">Hlavní stránka</a> 
| <a href="all_users.php">Seznam uživatelů</a> 
| <a href="index.php?action=odhlasit_se">Odhlásit se</a>
  </div>
  '
?>