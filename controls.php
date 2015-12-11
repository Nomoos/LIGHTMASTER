<?php

echo '<div class="popcontainer" id="controlpopcontainer">';
echo '<div class="popsubcontainer">';

$controls = mysqli_query($dataconection, "SELECT * FROM control_gateway WHERE area_ID_area =" . $_SESSION['zone']);
$i = 0;
while ($control = mysqli_fetch_array($controls)) {
    if ($i == 0 OR $i % $COUNTONPAGE == 0) {
        echo '<div class="rTable controls" id="controllist'.($i/$COUNTONPAGE+1).'" style="';
        if ($i != 0){echo 'display:none';};
        echo '">';
    }
    echo '<div class="rTableRow row"><div class="rTableCell controlcell" onclick="location=\'' . $_SERVER['SERVER_ROOT'] . '?control=' . $control['ID_control'] . '\'">' . $control['Name_control'] . '</div> <div class="rTableCell controlcell" onclick="location=\'' . $_SERVER['SERVER_ROOT'] . '?control=' . $control['ID_control'] . '\'">prejmenovat</div><div class="rTableCell controlcell" onclick="if(confirm(\'' . _("Opravdu chcete smazat zónu?") . '\')){console.log(\'' . $control['ID_control'] . '\')}else{console.log(\'Ne\')}">smazat </div></div>';
    $i++;
    if ($i % $COUNTONPAGE == 0) {
        echo '</div>';
    }

}

for($stranka=1;$stranka <= round($i/$COUNTONPAGE);$stranka++) {
    echo '<span ';
    if($stranka==1) {
        echo 'class="controllistpageactive" page='.$stranka.' ';
    }
    echo 'id="controllistpage'.$stranka.'" onclick="document.getElementById(\'controllist\'+document.getElementsByClassName(\'controllistpageactive\')[0].id.slice(12,document.getElementsByClassName(\'controllistpageactive\')[0].id.length)).style.display = \'none\';document.getElementsByClassName(\'controllistpageactive\')[0].className = \'\';document.getElementById(\'controllistpage'.$stranka.'\').className=\'controllistpageactive\';document.getElementById(\'controllist'.$stranka.'\').style.display =\'block\';">'.$stranka.'</span>';
}
echo '<div onclick="document.getElementById(\'controlpopcontainer\').style.display = \'none\';document.getElementById(\'map\').style.display = \'block\';" style="
    cursor: pointer;
">Zavřít</div>';
echo '</div>';
echo '</div>';
echo '</div>';

?>