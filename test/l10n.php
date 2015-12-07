<?php 
// výběr jazyka pro texty aplikace 
$lang = $DEFAULTLOCALE; // implicitní jazyk
$domain = $TRANSLATEDOMAIN; // implicitni domena
// změna preferovaného jazyka podle parametru v URL 
if (isset($_GET['loc'])) {
  $_SESSION['Locale'] = $_GET['loc'];
}
//preklady
// I18N support information here
if(isset($_SESSION['Locale'])){
  $lang = $_SESSION['Locale'];
}else{
  $lang = $DEFAULTLOCALE;
}
$domain = $domain."_".$lang;
// změna jazyka používaného knihovnou gettext 
putenv("LANG=$lang"); 
setlocale(LC_ALL, $lang);
bindtextdomain($domain, realpath("../locale"));
bind_textdomain_codeset($domain, "utf-8");
textdomain($domain);
?>