<?php

$result = mysqli_query($dataconection, "SELECT * 
FROM  `users` 
LEFT OUTER JOIN Rule_access AS Rule ON users.id = Rule.ID_user
WHERE users.id=".$_SESSION['id'].";");




$row = mysqli_fetch_array($result);
extract($row);
if($Super_admin==1)
{
$result = mysqli_query($dataconection, "SELECT ID_company, Company_name
FROM  `Company` ");
}else{
$result = mysqli_query($dataconection, "SELECT Company.ID_company AS ID_company,Company.Company_name AS Company_name FROM `users`
LEFT OUTER JOIN License_managment AS Managment ON users.id = Managment.ID_user
LEFT OUTER JOIN Company ON Company.ID_company = Managment.ID_company
WHERE users.id=".$_SESSION['id']."; 
");
}

?>
<script>
var select_company;
</script>

<div class="nav">
Společnost:
<select id="company" class="nav_select item" name="company" onchange='unselect_all();select_company=document.getElementById("company").value;draw_map();'>
<?php
$_SESSION['company']=array();
if(!empty($result)){
While( $row = mysqli_fetch_array($result) )
{
extract($row);

      $_SESSION['company'][$ID_company]=$Company_name;            
      echo '<option value="'.$ID_company.'">'.$Company_name.'</option>';

}
}else{
 $_SESSION['company']['Demo']="Demo";            
      echo '<option value="Demo">Demo</option>';
}
 
?>
    </select>   
<?php


  
  echo '

<div class="first item">
<a class="link" href="pravidla.php">Přistupová pravidla</a> 
</div>
<div class="last item">
<a class="link" href="index.php?action=odhlasit_se">Odhlásit se</a>
</div>
  </div>
  <script>document.getElementById("company").value;</script>
  '
?>