<?php



if(isset($_SESSION['zone'])){
$zoneid = $_SESSION['zone'];
}else{
$zoneid = $DEFAULTAREAID ;
}
echo '<div class="areas">';
$parentzoneid = $zoneid;
$parents = [];
$zonelevel = 1;
while ($parentzoneid){
    $parentzone=mysqli_query($dataconection, "SELECT * FROM area WHERE ID_area =".$parentzoneid);
    
   $parent = mysqli_fetch_array($parentzone);
$parents[$zonelevel] = '<a class="link area" href="?z='.$parent['ID_area'].'">'.$parent['Area_name'].'</a>';
if($parentzoneid!=$DEFAULTAREAID){
$parentzoneid = $parent['area_ID_area'];
}else{
$parentzoneid = False;
}
$zonelevel = $zonelevel+1;
}
$zonelevel = $zonelevel-1;
while($zonelevel > 0){
echo $parents[$zonelevel];
echo " > ";
$zonelevel = $zonelevel-1;
}

echo "<ul>";
$zones=mysqli_query($dataconection, "SELECT * FROM area WHERE area_ID_area =".$zoneid);
while($zone = mysqli_fetch_array($zones)) {
echo '<li><a class="link area" href="?z='.$zone['ID_area'].'">'.$zone['Area_name'].'</a></li>';
}
echo "</ul><br></div>" ;

?>