<?php
//$_GET['url'] = 'http://www.anity.net/manga/nineteen-twentyone';
if(isset($_GET['url'])) {	
	$pages = file_get_contents($_GET['url']);
	$tmp = explode('<caption><strong>', $pages);
	$name = explode('</strong>', $tmp[1]);
	$tmp = explode('<td class="left" scope="row">', $pages);
	$chapter = count($tmp)-1;
	if ($chapter>0) {
		echo json_encode(array('name'=>$name[0],'chapter'=>$chapter));
	} else {
		echo json_encode(array('name'=>'none','chapter'=>0));
	}
}
?>
