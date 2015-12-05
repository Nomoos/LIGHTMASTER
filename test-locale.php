<?php
// Include I18N support
require_once "locale.php";

echo _("Hello World!"), "<br>";
echo _("Testing Translation...");
function gettext_by_lang($lang, $word) {
    putenv("LC_ALL=$lang");
    setlocale(LC_ALL, $lang);
    bindtextdomain("lightmaster", "./locale");
    textdomain("lightmaster");
    return gettext($word);
}
$word = 'Hello World!';
$word_cs = gettext_by_lang('cs',  $word);
$word_en = gettext_by_lang('en',  $word);
echo '<br>'.$word_cs.'<br>'.$word_en;
?>