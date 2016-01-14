<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php
error_reporting(1);
$siteDomain = array('chatbox'=>'life-it.ath.cx','myself'=>'it-my.selfip.info');
$urlDomain = 'http://'.$_SERVER['SERVER_NAME'].'/';
$docDomain = $_SERVER['DOCUMENT_ROOT'].'/';
$folderSite = NULL;
//Setting Configuration Site
if(file_exists($docDomain.$folderSite.'config/default.Site.ini')) {
	$config = parse_ini_file($docDomain.$folderSite.'config/default.Site.ini', true);
} else {
	echo '<h1>Error: Config not Found.</h1>';
}
?>
<head>
<?php
switch($_SERVER['SERVER_NAME'])
{
	case $siteDomain['myself']:
		// It'my Selfip Site.
		$folderSite = 'site.selfip.info/';
		include_once($docDomain.$folderSite.'configuration.inc.php');
		break;	
	case $siteDomain['chatbox']:
		// Life Chat Box Site.		
		$folderSite = 'site.ath.cx/';
		include_once($docDomain.$folderSite.'configuration.inc.php');
		break;
	default:
		// Default Site Main link as '192.168.1.9' etc.
		$folderSite = 'none.site/';
		$siteTitle = "Not Found Site";
		$siteIcon = "favorite.ico";
		break;
}
?>
<title><?php echo $siteTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $config['site']['charset']; ?>">
<meta name="description" content="<?php echo $siteDescription; ?>" />
<meta name="keywords" content="<?php echo $sitekeywords; ?>" />
<link rel="icon" href="<?php echo $urlDomain.$folderSite; ?>images/<?php echo $siteIcon; ?>" />
<link rel="shortcut icon" href="<?php echo $urlDomain.$folderSite; ?>images/<?php echo $siteIcon; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $urlDomain.$folderSite; ?>/include/css/style_css.css">

<!--Plugins Site-->
<script type="text/javascript" src="<?php echo $urlDomain; ?>plugins/jquery-1.4.3.js"></script>
<script type="text/javascript" src="<?php echo $urlDomain; ?>plugins/jquery.pngFix.pack.js"></script>

<?php 
if(is_readable($docDomain.$folderSite)) {
	require_once($docDomain.$folderSite.'header.php');	
	echo "</head>\n<body>\n";
	require_once($docDomain.$folderSite.'index.php');
} else {
	echo '<h1>Error: Not Found Site Directory.</h1>';
}
?>
</body>
</html>