<?php



if (isset($_POST['changerule'])) {
    $rules = array();
    foreach ($_POST as $kluc => $hodnota) {
        $retezec = explode("*", $kluc);

        if (count($retezec) == 2) {
            if (isset($rules[$retezec[0]]) == 0) {
                $rules[$retezec[0]] = array();
            }
            $rules[$retezec[0]][$retezec[1]] = "1";

        }
    }
    //print_r($rules);
    foreach ($rules as $account => $info) {
        foreach (Array("Super_admin", "View_lamp", "Edit_lamp", "Edit_rule") as $key) {
            if (array_key_exists($key, $info)) {
            } else {
                $info[$key] = 0;
            }
        }


        $first = True;
        foreach ($info as $name => $rule) {
            if ($first) {
                $temp = $name . "=" . $rule;
                $first = False;
            } else {
                $temp = $temp . "," . $name . "=" . $rule;
            }
        }

        $sql = "UPDATE `rule_access` SET " . $temp . "\n"
            . "WHERE users_ID= '" . $account . "' and ";
        echo $sql;
        echo "<br>";
        mysqli_query($dataconection, $sql);
        echo mysqli_affected_rows($dataconection);
        echo "<br>";

    }
}

/* cyklus prebehne cele pole, v premennej $kluc bude nazov kluca, v premennej
$hodnota bude hodnota prvku pola prisluchajuca aktualnemu klucu */


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
$result = mysqli_query($dataconection, "SELECT * 
FROM  `users` 
LEFT OUTER JOIN rule_access AS Rule ON users.id = Rule.users_ID
WHERE Rule.company_ID_company = ".$_SESSION['company']);
echo '<div class="content_container">';
echo "<div class='trans_table'>";
echo "<table class='rule_table'><tr class='frist_table_row row'><td class='frist_cell head_row'>Jméno</td><td class='head_row'>Email</td>";
echo "<td>Company</td>";
echo "<td class='head_row'>Super admin</td><td class='head_row'>View lamp</td><td class='head_row'>Edit lamp</td><td class='head_row'>Edit rule</td></tr>";

echo "<form action=\"\" method=\"POST\">";
echo $inputcompanyname;
$even = 'odd';
While ($row = mysqli_fetch_array($result)) {
    extract($row);


    echo "<tr class='table_row_" . $even . " row'>";
    echo "<td class='frist_cell " . $even . " cell'>" . $name . "</td>";
    echo "<td class='" . $even . " cell'>" . $email . "</td>";
    echo "<td>" . $company_ID_company . "</td>";

    If ($Super_admin == 1) {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*Super_admin" . "\" value=\"1\" type=\"checkbox\" checked ></td>";
    } else {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*Super_admin" . "\" value=\"1\" type=\"checkbox\"></td>";
    }
    If ($View_lamp == 1) {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*View_lamp" . "\" value=\"1\" type=\"checkbox\" checked ></td>";
    } else {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*View_lamp" . "\" value=\"1\" type=\"checkbox\"></td>";
    }
    If ($Edit_lamp == 1) {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*Edit_lamp" . "\" value=\"1\" type=\"checkbox\" checked ></td>";
    } else {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*Edit_lamp" . "\" value=\"1\" type=\"checkbox\"></td>";
    }
    If ($Edit_rule == 1) {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*Edit_rule" . "\" value=\"1\" type=\"checkbox\" checked ></td>";
    } else {
        echo "<td class='" . $even . " cell'><input name=\"" . $id . "*Edit_rule" . "\" value=\"1\" type=\"checkbox\"></td>";
    }
    echo "";
    echo "</tr>";
    if ($even == 'odd') {
        $even = 'even';
    } else {
        if ($even == 'even') {
            $even = 'odd';
        }
    }
}
echo "</table>";
echo "<input class='buttons' type=\"submit\" name=\"changerule\" value=\"Změnit oprávnění\">";
echo "</form>";
echo "</div></body></html>";
?>
<script>
    function unselect_all() {

    }
    function draw_map() {

    }
</script>
