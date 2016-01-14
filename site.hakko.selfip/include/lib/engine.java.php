<?php
error_reporting(0);
foreach (glob("../../../plugins/lib/*.php") as $filename) { include_once $filename; }
require_once('../engine.php');
$GLOBALS['Domain'] = 'http://hakkomew.selfip.info/site.hakko.selfip/';
$GLOBALS['Document'] = 'C:/AppServ/www/LifeItMy/site.hakko.selfip/';
$HaKko = new Engine();
$reqPath = new RequestPath();
?>