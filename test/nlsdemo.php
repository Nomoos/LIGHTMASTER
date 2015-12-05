<?php 
// zákaz ukládání stránky do vyrovnávací paměti 
header("Cache-Control: no-cache"); 
// detekce jazyka a inicializace lokalizačního mechanismu 
require_once "l10n.php"; 
?> 
<!DOCTYPE HTML PUBLIC ‘-//W3C//DTD HTML 4.0 Transitional//EN’> 
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8″> 
<title><?php echo _("Internationalized form")?></title> 
</head> 
<body> 
<?php if (!IsSet($_GET["n"])): ?> 
<h1><?php echo _("Welcome!")?></h1> 
<form action="nlsdemo.php"> 
<?php echo _("Your preferred natural number: ")?> 
<input name="n"> 
<input type="submit" value="<?php echo _("Submit")?>"> 
</form> 
<?php else: ?> 
<?php echo _("Here is your <i>magic matrix</i>.")?> 
<table border="1″> 
<?php 
  $n = abs($_GET["n"]); 
  for ($i=1; $i<=$n; $i++) 
  { 
    echo "<tr>"; 
    for ($j=1; $j<=$n; $j++) 
      echo "<td>" . $i * $j . "</td>"; 
    echo "</tr>"; 
  } 
?> 
</table> 
<a href="nlsdemo.php"><?php echo _("Generate new matrix")?></a> 
<?php endif ?> 
<div align="center"> 
  <a href="nlsdemo.php?changelang=en">English interface</a> | 
  <a href="nlsdemo.php?changelang=cs"><?php echo _("Czech interface")?></a> | 
  <a href="nlsdemo.php?changelang=auto"><?php echo _("Autodetect language")?></a> 
</div> 
</body> 
</html>