<?php

switch($_GET['mode'])
{	
	// Check manga Name and Total Chapter 
	case 'chk';			
		$pages = file_get_contents($_GET['url']);
		$name = explode('<h1 id="gn">', $pages);
		$name = explode('</h1>', $name[1]);		
		$total = explode('<p class="ip">', $pages);
		$total = explode('images', $total[1]);
		$total = explode('of', $total[0]);
		$total = (int)$total[1];		
		$part = 1;
		
		if ($total>0) {
			echo json_encode(array('name'=>$name[0],'chapter'=>$total,'part'=>$part));
		} else {
			echo json_encode(array('name'=>'none','chapter'=>0));
		}
	break;	
	// Get list Manga URL
	case 'list';
		$pages = file_get_contents($_GET['url']);
		$name = explode('<h1 id="gn">', $pages);
		$name = explode('</h1>', $name[1]);
		//for($p=0;$p<$ploop;$p++) {
			$pages = file_get_contents($_GET['url']);			
			$list = explode('<div id="gdt">', $pages);
			$list = explode('margin:0px auto 10px', $list[1]);			
			$list = explode('<a href="', $list[0]);
			//$total_url = array();
			for($i=1;$i<=20;$i++) {			
				$url = explode('"><img', $list[$i]);
				if(count($url)==2) {
					$total_url[] = $url[0];
				}
			}
		//}
		$chapter[] = $total_url;
		$chapter[] = ereg_replace('[\/\?\!\'\"]','',trim($name[0]));
		echo json_encode($chapter);
	break;
	// Created Folder Manga and Folder Chapter
	case 'create';
		$folderName = iconv('utf-8','tis-620',$_GET['dir']);
		$directoryName = iconv('utf-8','tis-620',$_GET['chapter']);		
		if(!is_readable($folderName)) {
			mkdir($folderName,0777);
		}
		if(!is_readable($folderName.'\\'.$directoryName)) {
			mkdir($folderName.'\\'.$directoryName,0777);
		}
		$dir = explode("\\",$_GET['dir']);
		echo json_encode(array('dir'=>$dir[count($dir)-1],'chapter'=>$_GET['chapter']));
	break;
	// Get List Image URL Form Chapter
	case 'image';
		ini_set('memory_limit', '1024M');
		set_time_limit(3600);
		$imgName = array();
		$imgName[0] = $_GET['manganame'];
		$imgName[1] = count($_GET['mangaurl'])+1;		
		foreach ($_GET['mangaurl'] as $pages) {
			//do {
				$page = file_get_contents($pages);
				$image = explode('</iframe>', $page);
				//if(count($image)==1) { sleep(1); }
			//} while(count($image)==1);
			$image = explode('"><img src="', $image[1]);
			$image = explode('" style="', $image[1]);
			$imgName[] = $image[0];
		}
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}
?>
