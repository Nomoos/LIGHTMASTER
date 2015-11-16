<?php
require_once 'pristup.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">

	<title>Map</title>
<!-- js knihovny -->
<script src="lib/leaflet/leaflet.js"></script>
<script src="lib/js/jquery.js"></script>
<script src="module/myscript.js"></script>
<!-- styly -->
<link rel="stylesheet" href="lib/leaflet/leaflet.css" />
<link rel="stylesheet" href="css/css/kraken.css" />
<link rel="shortcut icon" href="img/sviti.png" />	
<style>
      body{
        background: #FFE4C2;
      }
      .buttons:hover{
      background: rgb(255, 228, 194);
    }
    .buttons{
    padding: 3px;
    border: 2px;
    background: #F3D2A7;

      }
      #map {
        height: 500px;
        width: 88%;
        position:absolute;
      }
      .item{
        display: inline;
      }
      last{
        margin-right: 5px;
      }
      .nav, .nav_select, .menu, .menu_container, .item{
      background: #C99F6A;
      font-family: "Helvetica Neue", Arial, sans-serif;
      color: white;
      }
      .nav{
        float:right;         
      }
      .nav_select{
        display: inline;
        width:auto;
        margin-bottom: 0px;
        border-bottom: 0px;
        border-top: 0px;
        border-color: white;
      }
      .menu{
        float:left;
        padding:3px;
      }
      .menu_container{
      }
      a.link {
        padding-right: 5px;
        padding-left: 5px;
        padding-bottom: 7px;
        padding-top: 6px;
        text-decoration: none;
        font-family: "Helvetica Neue", Arial, sans-serif;
        color: white;
      }
      a.link:hover {
        background: #FFE4C2;
        text-decoration: none;
        color: black;
      }
      a.link:active {
      background: #F3D2A7;
      color: white;
      }
      .space50{
      height : 50px;
      }
      .space31{
      height : 31px;
      }
      .form_container{
      color: white;
      margin-top: 30px;
      float: right;
      padding:5px;
      width: 15%;
      position: relative;
      background: #C99F6A;
      width: auto;
      }
      .container{
      height: 581px;
      /*background:#DEB887; */
      padding-left: 3px;
      padding-right: 3px;
      }
      .newlamp{
      
      }
      .nazev_atributu {
      font-weight: bold;
      color: bisque;
      }
      .pulka{
      width:50%;
      }
      .grey{
      background:lightgray;
      }
      .red{
      background:lightcoral;
      }
      .green{
      background:lightgreen;
      }
      .lamp_table{
    border-collapse: collapse;
      }
      
    .table_container {
  /*float: left; */
  padding-left: 5px;
  padding-right: 5px;
  background: rgb(218, 160, 85);
  padding-bottom: 5px;
}
    .cell {
      text-align: right;
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

.lamps{

 background:rgb(218, 160, 85);
}

.content_container{
 background:rgb(218, 160, 85) ;

}
       
</style>

	
</head>

<body>
<script>
document.documentElement.className+=' js';
</script>

<div class="container">
<div class="space50">
</div>
<div class="menu_container">
<?php
  require_once 'module/usermenu.php';
  require_once 'module/menu.php';
?>
</div>
<script>
<?php
If(!empty($_POST)){

//spolecnost
If(!empty($_SESSION['company'])){
echo 'document.getElementById("company").value='.$_SESSION['company'].';';
}else{
echo 'console.log("Je POST není vybraná společnost.");';
}
}
echo 'select_company=document.getElementById("company").value;';
?>
</script>
<div id="snippet--flashes"></div>

<div id="content_container" class="content_container">
<div id="table" class="table_container"></div>


<?php
echo '<script>var table_list=[];';
foreach($_SESSION['company_list'] as $ID_Company => $name ){
if(!empty($ID_Company)){
echo "table_list[".$ID_Company."] = '<div class=\"table_list\"><table class=\"lamp_table\">";

$controls=mysqli_query($dataconection, "SELECT ID_control,Name_control FROM control_gateway WHERE ID_company = ".$ID_Company." AND x_deleted = '0' ORDER BY ID_control;");

while ($company = mysqli_fetch_array($controls, MYSQLI_NUM)) {



//echo "table_list[".$ID_Company."] = table_list[".$ID_Company."]+'<table class=\"lamp_table\"><caption class=\"caption\">Kontrolní bod ".$company[1]."</caption>";
echo "<caption class=\"caption\">Kontrolní bod ".$company[1]."</caption>";


$even=0;
echo '<tr class="frist_table_row row"><th class="frist_cell head_row cell">ID</th><th class="head_row cell">Šířka</th><th class="head_row cell">Délka</th><th class="head_row cell">Zapnutá</th><th class="even cell">Aktuální vytížení</td><th class="even cell">Nastavené vytížení</td><td class="even cell">Nastavený plán</td></tr>';

$result = mysqli_query($dataconection, "SELECT lamp.lat,lamp.long,lamp.id,Gate.Name_control,lamp.is_enabled,lamp.actual_workload,lamp.set_workload,Workload_plan.PLAN_NAME FROM `Company`
LEFT OUTER JOIN Control_gateway AS Gate ON Gate.ID_company = Company.ID_company
LEFT OUTER JOIN lamp ON lamp.ID_control = Gate.ID_control
LEFT OUTER JOIN Workload_plan ON Workload_plan.ID_PLAN = lamp.ID_workload
WHERE Gate.ID_control='".$company[0]."' AND lamp.x_deleted = '0';");
if (!$result) {
    die('Invalid query: ' . mysqli_error($dataconection));
}

while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
if(empty($row[7])){
$row[7]="Manual";
}
if($even==1){
echo '<tr class="table_row_even row"><td class="frist_cell even cell">'.$row[2].'</td><td class="even cell">'.$row[0].'</td><td class="even cell">'.$row[1].'</td><td class="even cell">'.$row[4].'</td><td class="even cell">'.$row[5].'</td><td class="even cell">'.$row[6].'</td><td class="even cell">'.$row[7].'</td></tr>';
$even =0;
}else{
echo '<tr class="table_row_odd row"><td class="frist_cell odd cell">'.$row[2].'</td><td class="odd cell">'.$row[0].'</td><td class="odd cell">'.$row[1].'</td><td class="odd cell">'.$row[4].'</td><td class="even cell">'.$row[5].'</td><td class="even cell">'.$row[6].'</td><td class="even cell">'.$row[7].'</td></tr>';
$even =1;
}

}

}
echo "</table>';";
echo "table_list[".$ID_Company."] = table_list[".$ID_Company."]+'</div>';";
}
}
?>
document.getElementById("table").innerHTML=table_list[select_company];
</script>
</div>
<script>
function unselect_all(){
document.getElementById("table").innerHTML=table_list[select_company]
}
function draw_map(){
}

if(select_company==""){

document.getElementById("content_container").innerHTML='NEJSTE ČLENEM ŽÁDNÉ SPOLECNOSTI POZÁDEJTE ADMINA VASÍ SPOLECNOSTI O PŘIDÁNI NEBO SI ZAKUPTE LICENCI PRO NOVOU SPOLEČNOST';
}

</script>
  </body>
</html>
