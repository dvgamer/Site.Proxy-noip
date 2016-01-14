<?php
error_reporting(-1);
date_default_timezone_set("Asia/Bangkok");
foreach (glob("plugins/lib/*.php") as $filename) { include_once $filename; } 
foreach (glob($folderSite."/include/*.php") as $filename) { include_once $filename; }
$session = new Session();
?>