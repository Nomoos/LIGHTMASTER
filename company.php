<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">

    <title>Map</title>
</head>
<body>

<?php


if (isset($_SESSION['company'])) {
    $companyid = $_SESSION['company'];
}
if ($_SESSION['id']) {
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
        $result = mysqli_query($dataconection, "SELECT  rule_access.companyadmin AS is_admin,licensetype.License_type,company.ID_company AS ID_company,company.Company_name AS Company_name FROM `users`
LEFT OUTER JOIN license_managment ON users.id = license_managment.users_id
LEFT OUTER JOIN company ON company.ID_company = license_managment.company_ID_company
LEFT OUTER JOIN licensetype ON licensetype.License_type = license_managment.licensetype_license_type
LEFT OUTER JOIN rule_access ON rule_access.company_ID_company = company.ID_company
WHERE users.id=" . $_SESSION['id'] . ";
");
    }

    echo '<div style="text-align: center;"><div class="companies">';
    $DEMO = True;
    $companyadmin = False;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if (isset($row['ID_company'])) {
            if ($row['ID_company'] != $DEMOCOMPANYID) {
                echo '<a class="link company" href="?action=';
                if(isset($row['ID_license'])) {
                    if($row['ID_license']!=0) {
                        echo '1';
                    }else{
                        echo '2';
                    }
                }else{
                    echo '2';
                }
                echo '&c='.$row['ID_company'].'">' . $row['Company_name'];
                if(isset($row['ID_license'])) {
                    if($row['ID_license']==0) {
                        echo ' (Neaktivní)';
                    }
                }else{
                    echo ' (Neaktivní)';
                }
                echo '</a>';
                if(isset($row['is_admin'])) {
                    if($row['is_admin']==1) {
                        echo ' <a class="link company" href="?action=2&c=' . $row['ID_company'] . '">Editovat</a>';
                        $companyadmin = True;
                    }
                    }
                if(!$companyadmin){
                    echo ' <a class="link company" href="?action=2&c=' . $row['ID_company'] . '">Podrobnosti</a>';
                }
                    echo "<br>";
                $DEMO = False;
                $companyadmin = False;
            }
        }
    }
    if ($DEMO) {
        echo 'Nemáte oprávnění k žádné společnosti, ale můžete se podívat na:<br>';
        echo '<a class="link plans" href="?action=1&c=' . $DEMOCOMPANYID . '">Demo</a>';
    }
    echo "<br></div></div>";
} else {
    echo '<script>window.location.href="' . $_SERVER['SERVER_ROOT'] . '"</script>';
}


?>
</body>
</html>
