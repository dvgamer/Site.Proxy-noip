<?php
require_once('engine.java.php');
$HaKko->InsertComment($_POST['userid'], $_POST['manga'], $_POST['comment'], $_SERVER['REQUEST_TIME'], $_SERVER['REMOTE_ADDR']);
?>