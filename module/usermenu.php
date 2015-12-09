<?php
if ($_SESSION['id']){
$result = mysqli_query($dataconection, "SELECT * 
FROM  `users` 
LEFT OUTER JOIN rule_access AS Rule ON users.id = Rule.users_ID
WHERE users.id=" . $_SESSION['id'] . ";");


$row = mysqli_fetch_array($result);
extract($row);
if ($Super_admin == 1) {
    $result = mysqli_query($dataconection, "SELECT ID_company, Company_name
FROM  `company` ");
} else {
    $result = mysqli_query($dataconection, "SELECT *,company.ID_company AS ID_company,company.Company_name AS Company_name FROM `users`
LEFT OUTER JOIN license_managment ON users.id = license_managment.users_id
LEFT OUTER JOIN company ON company.ID_company = license_managment.company_ID_company
WHERE users.id=" . $_SESSION['id'] . ";
");
}

?>
<script src="module/myscript.js"></script>
<script>
    var select_company;
    function switch_company() {
        <?php
        echo 'window.location.href="'.$_SERVER['SERVER_ROOT'].'?c="+document.getElementById(\'company\').value;';
        ?>
    }
</script>

<div class="nav">
    <?php
    echo _('Společnost:');

    echo '<select id="company" class="nav_select item" name="company" onchange="switch_company();">';

    $_SESSION['company_list'] = array();


    $DEMO = True;
    while ($row = mysqli_fetch_array($result)) {
        if (isset($row['ID_company'])) {
            if ($row['ID_company'] != $DEMOCOMPANYID or $Super_admin == 1) {
                extract($row);
                $_SESSION['company_list'][$ID_company] = $Company_name;
                echo '<option value="' . $ID_company . '">' . $Company_name . '</option>';
            }
            $DEMO = False;
        }
    }

        $_SESSION['company_list'][1] = "Demo";
        echo '<option value="' . $DEMOCOMPANYID . '">Demo</option>';


    ?>
    </select>
    <?php


    echo '

<div class="first item">
<a class="link" href="?p=copanyprofile">'._('Přistupová pravidla').'</a>
</div>
<div class="last item">
<a class="link" href="index.php?action=odhlasit_se">'._('Odhlásit se').'</a>
</div>'
        ?>
</div>

<?php

    If (!empty($_SESSION['company'])) {
        echo '<script>document.getElementById("company").value=' . $_SESSION['company'] . ';</script>';
    } else {
        if ($_SESSION['page'] != 'company') {
            echo '<script>window.location.href="' . $_SERVER['SERVER_ROOT'] . '?p=company"</script>';
        }
    }

    } else {
        echo _('Neprihlasen');
    }
    ?>

