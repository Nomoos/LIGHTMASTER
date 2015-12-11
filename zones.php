<?php

echo '<div class="popcontainer" id="zonepopcontainer">';
echo '<div class="popsubcontainer">';

$zones = mysqli_query($dataconection, "SELECT * FROM area WHERE company_ID_company =" . $_SESSION['company']);
$i = 0;
while ($zone = mysqli_fetch_array($zones)) {
    if ($i == 0 OR $i % $COUNTONPAGE == 0) {
        echo '<div class="rTable areas" id="arealist'.($i/$COUNTONPAGE+1).'" style="';
        if ($i != 0){echo 'display:none';};
        echo '">';
    }
    echo '<div class="rTableRow row"><div class="rTableCell areacell" onclick="location=\'' . $_SERVER['SERVER_ROOT'] . '?z=' . $zone['ID_area'] . '\'">' . $zone['Area_name'] . '</div> <div class="rTableCell areacell" onclick="location=\'' . $_SERVER['SERVER_ROOT'] . '?z=' . $zone['ID_area'] . '\'">prejmenovat</div><div class="rTableCell areacell" onclick="if(confirm(\'' . _("Opravdu chcete smazat zónu?") . '\')){console.log(\'' . $zone['ID_area'] . '\')}else{console.log(\'Ne\')}">smazat </div></div>';
    $i++;
    if ($i % $COUNTONPAGE == 0) {
        echo '</div>';
    }

}

for($stranka=1;$stranka <= round($i/$COUNTONPAGE);$stranka++) {
    echo '<span ';
    if($stranka==1) {
        echo 'class="arealistpageactive" page='.$stranka.' ';
    }
    echo 'id="arealistpage'.$stranka.'" onclick="document.getElementById(\'arealist\'+document.getElementsByClassName(\'arealistpageactive\')[0].id.slice(12,document.getElementsByClassName(\'arealistpageactive\')[0].id.length)).style.display = \'none\';document.getElementsByClassName(\'arealistpageactive\')[0].className = \'\';document.getElementById(\'arealistpage'.$stranka.'\').className=\'arealistpageactive\';document.getElementById(\'arealist'.$stranka.'\').style.display =\'block\';">'.$stranka.'</span>';
}
echo '<div onclick="document.getElementById(\'zonepopcontainer\').style.display = \'none\';document.getElementById(\'map\').style.display = \'block\';" style="
    cursor: pointer;
">Zavřít</div>';
echo '</div>';
echo '</div>';
echo '</div>';

?>