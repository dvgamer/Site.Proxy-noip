<link rel="stylesheet" type="text/css" href="<?php echo $urlDomain.$folderSite; ?>include/css/template_css.css" />
<script type="text/javascript" src="<?php echo $urlDomain.$folderSite; ?>include/jquery.hakko.js"></script>
<?php
$reqTitle = new RequestPath();
$manga = NULL;
if($reqTitle->Level(1)) {
	$manga = ' :: ';
	if($reqTitle->Level(1)=='board') {
		$manga .= 'Webboard';
	} elseif($reqTitle->Level(1)=='info') {
		$manga .= 'Status';
	} else {
		$manga .= $reqTitle->Level(1);
		$fileTarget = $GLOBALS['Document'].'translator/[Content] Database/'.$reqTitle->Level(1).'.txt';
		$description = _MANGA_NONE;
		$keywords = 'hakko,manga,hakkomew,khem,kaow,';
		if(file_exists($fileTarget)) { 
			$raw = file_get_contents($fileTarget);
			$tmpData = explode('|',iconv('tis-620','utf-8',$raw));
			$description = $tmpData[3];
			$keywords .= $tmpData[1];
		}
	}
} else {
	$fileTarget = $GLOBALS['Document'].'article/abuntme.txt';
	if(file_exists($fileTarget)) { 
		$description = ereg_replace('(\[)[A-Za-z]{0,5}(])|(\[\/)[A-Za-z](])|[\"]',  '', file_get_contents($fileTarget));
	}
}
if($reqTitle->Level(2)) { $manga .= ' - '.ereg_replace('-',' ',$reqTitle->Level(2)); }
?>
<title><?php echo _HEAD_TITLE.$manga; ?></title>
<meta name="Description" content="<?php echo $description; ?>" />
<meta name="Keywords" content="<?php echo $keywords; ?>" />
<meta http-equiv="Keywords" content="<?php echo $keywords; ?>" />
<?php
unset($reqTitle,$manga);
?>
