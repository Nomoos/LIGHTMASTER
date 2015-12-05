<?php


if (isset($_SESSION['zone'])) {
    $zoneid = $_SESSION['zone'];
} else {
    $zoneid = $DEFAULTAREAID;
}
echo '<div class="popcontainer" id="zonepopcontainer">';
echo '<div class="popsubcontainer">';
echo '<div class="areas">';
$zones = mysqli_query($dataconection, "SELECT * FROM area WHERE company_ID_company =" . $_SESSION['company']);
while ($zone = mysqli_fetch_array($zones)) {
    echo '<div class="table_row"><a class="link area" href="?z=' . $zone['ID_area'] . '">' . $zone['Area_name'] . '</a> <a class="link area" href="?z=' . $zone['ID_area'] . '&action=1">vybrat</a> <!--<a class="link area" href="?z=' . $zone['ID_area'] . '&action=2">--> upravit <!--</a>--><!--<a class="link area" href="?z=' . $zone['ID_area'] . '&action=2">--> smazat <!--</a>--></div>';
}
echo '</div>';
echo '<div onclick="document.getElementById(\'zonepopcontainer\').style.display = \'none\';" style="
    cursor: pointer;
">Zavřít</div>';
echo '</div>';
echo '</div>';
//test end

//old tree areas
//while ($parentzoneid) {
//    $parentzone = mysqli_query($dataconection, "SELECT * FROM area WHERE ID_area =" . $parentzoneid);
//
//    $parent = mysqli_fetch_array($parentzone);
//    $parents[$zonelevel] = '<a class="link area" href="?z=' . $parent['ID_area'] . '">' . $parent['Area_name'] . '</a>';
//    if ($parentzoneid != $DEFAULTAREAID) {
//        $parentzoneid = $parent['area_ID_area'];
//    } else {
//        $parentzoneid = False;
//    }
//    $zonelevel = $zonelevel + 1;
//}
//$zonelevel = $zonelevel - 1;
//while ($zonelevel > 0) {
//    echo $parents[$zonelevel];
//    echo " > ";
//    $zonelevel = $zonelevel - 1;
//}
//if (!isset($_SESSION['zoneshow'])) {
//    echo "<ul>";
//    $zones = mysqli_query($dataconection, "SELECT * FROM area WHERE area_ID_area =" . $zoneid);
//    while ($zone = mysqli_fetch_array($zones)) {
//        echo '<li><a class="link area" href="?z=' . $zone['ID_area'] . '">' . $zone['Area_name'] . '</a> <a class="link area" href="?z=' . $zone['ID_area'] . '&action=1">vstoupit</a> <!--<a class="link area" href="?z=' . $zone['ID_area'] . '&action=2">--> editovat(neaktivni) <!--</a>--><!--<a class="link area" href="?z=' . $zone['ID_area'] . '&action=2">--> odstranit(neaktivni) <!--</a>--></li>';
//    }
//    echo '<!-- <a class="link area" href="?z=new&action=3"> -->pridat(neaktivni)<!--</a>--></ul><br></div>';
//}
//end old tree areas
?>