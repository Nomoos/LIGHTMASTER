<?php

session_start();

require_once('kcaptcha.php');

$captcha = new KCAPTCHA();

$_SESSION['captcha_keystring'] = $captcha->getKeyString();

?>