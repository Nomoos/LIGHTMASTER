<?php
//pripojeni k mySQL
include "db.php";
?>

	</head>
	<body>

<?php

$query = "
SELECT en.trans_section,cs.trans_text,en.trans_text,fr.trans_text,sp.trans_text 
FROM php_translate AS en

LEFT OUTER JOIN (SELECT * FROM php_translate WHERE trans_lang='cs') as cs ON en.trans_section=cs.trans_section
LEFT OUTER JOIN (SELECT * FROM php_translate WHERE trans_lang='fr') as fr ON en.trans_section=fr.trans_section
LEFT OUTER JOIN (SELECT * FROM php_translate WHERE trans_lang='sp') as sp ON en.trans_section=sp.trans_section
WHERE en.trans_lang='en'
";

$result = mysqli_query($dataconection, $query);
//}
// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$result) {
    $message  = 'Invalid query: ' . mysqli_error($dataconection) . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}




print "<p>";


echo "<div >";
echo "<div class='trans_table'>";
echo "<table><tr><td>KeyWord</td><td>Cesky</td><td>Anglicky</td><td>Francouyky</td><td>Spanelsky</td></tr>";

while($pole = mysqli_fetch_array($result))
{
echo "<tr>";
//$a je pocet jazyku + 1 kvuli dotazu na klicove slovo
for($a=0;$a<5;$a++){
    echo "<td><input type= 'text'name='".$a."' value='".$pole[$a]."'></td>";
    //echo $a."/".$pole[$a];
    
}
print "</tr>";
}
echo "</table></div>";

?>