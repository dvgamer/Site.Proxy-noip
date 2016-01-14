<?php
if(isset($_GET['url'])) {
	if(count(explode('/',$_GET['url']))>5) {
		$tmp = explode('/',$_GET['url']);
		for($i=0;$i<count($tmp)-1;$i++){
			if ($i==count($tmp)-2) { 
				$url .= $tmp[$i];		
			} else {
				$url .= $tmp[$i].'/';		
			}
		}				
		$_GET['url'] = $url;
		$chapter = $tmp[count($tmp)-1];
		$part = 1;
	}
	$pages = file_get_contents($_GET['url']);
	$tmp = explode('<div class="title"><h3>', $pages);
	$name = explode('</h3>', $tmp[1]);
	$tmp = explode("<li><div class='chapter-name'>", $pages);
	if(count(explode('/',$_GET['url']))<6) {
		$chapter = count($tmp)-1;
	}
	
	if ($chapter>0) {
		echo json_encode(array('name'=>$name[0],'chapter'=>$chapter,'part'=>$part));
	} else {
		echo json_encode(array('name'=>'none','chapter'=>0));
	}
}
?>
