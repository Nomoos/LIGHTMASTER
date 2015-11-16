<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">

	<title>Map</title>
  </head>
  <body>

<?php



if(isset($_SESSION['company'])){
$companyid = $_SESSION['company'];
}
if($_SESSION['id']){
$result = mysqli_query($dataconection, "SELECT * 
FROM  `users` 
LEFT OUTER JOIN rule_access AS Rule ON users.id = Rule.users_ID
WHERE users.id=".$_SESSION['id'].";");



$row = mysqli_fetch_array($result);
extract($row);
if($Super_admin==1)
{
$result = mysqli_query($dataconection, "SELECT ID_company, Company_name
FROM  `company` ");
}else{
$result = mysqli_query($dataconection, "SELECT *,company.ID_company AS ID_company,company.Company_name AS Company_name FROM `users`
LEFT OUTER JOIN license_managment ON users.id = license_managment.users_id
LEFT OUTER JOIN company ON company.ID_company = license_managment.company_ID_company
WHERE users.id=".$_SESSION['id']."; 
");
}

echo '<center><div class="companies">';
$DEMO=True;
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
if(isset($row['ID_company'])){
if($row['ID_company']!=$DEMOCOMPANYID){
echo '<a class="link plans" href="?c='.$row['ID_company'].'">'.$row['Company_name'].'</a>';
echo "<br>";
$DEMO=False;
}
}
}
if($DEMO){
echo 'Nemáte oprávnění k žádné společnosti, ale můžete se podívat na:<br>';
echo '<a class="link plans" href="?c='.$DEMOCOMPANYID.'">Demo</a>';
}
echo "<br></div></center>";
}else{
echo '<script>window.location.href="'.$_SERVER['SERVER_ROOT'].'"</script>';
}


?>
</body>
</html>
