<?php
//$_GET['url'] = 'http://www.nekopost.net/project/Manga/saikin_imouto_no_yousu_ga_chotto_okashiin_daga';
//$_GET['mangaurl'] = 2;
//$_GET['dir'] = 'C:\AppServ\www\LifeItMy\tmp_file\[Manga] Ao no exorcist';
switch($_GET['mode'])
{	
	// Check manga Name and Total Chapter 
	case 'chk';
		$pages = file_get_contents($_GET['url']);
		$chk = explode('/',$_GET['url']);
		if(count($chk)>6) {
			$name = explode('<title>Nekopost &bull;', $pages);
			$name = explode('</title>', $name[1]);
			$chapter = 1;
			$part = 1;
		} else {						
			$name = explode('<h1>Manga/&nbsp;<b>', $pages);
			$name = explode('</b>', $name[1]);
			$tmp = explode("<div class='list_chapter_no'>", $pages);	
			$chapter = count($tmp)-1;
		}
			
		if ($chapter>0) {
			echo json_encode(array('name'=>$name[0],'chapter'=>$chapter,'part'=>$part));
		} else {
			echo json_encode(array('name'=>'none','chapter'=>0));
		}
	break;	
	// Get list Manga URL
	case 'list';
		$pages = file_get_contents($_GET['url']);
		$chk = explode('/',$_GET['url']);
		if(count($chk)<7) {
			$chapterName = explode("<div class='list_chapter_read'>", $pages);
			$chapter = array();
			foreach($chapterName as $i=>$name){
				if($i>1) {					
					$list = explode("<div class='list_chapter_no'>",$name);
					$list = explode("<a href='",$list[1]);
					$folder = explode("'>",$list[1]);
					$folder = explode("</a>",$folder[1]);					
					$url = explode("| <a href='",$name);
					if(count($url)<=1) {
						$url = explode("<a href='",$name);
					}
					$url = explode("'>Read",$url[1]);					
					// URL Manga
					$chapter[] = 'http://www.nekopost.net/'.$url[0].'';
					// Name Manga
					$chapter[] = trim($list[0]).' '.ereg_replace('[\!\?\'\"\/\#\:]|[\.]{2,}',' ',$folder[0]);
				}
			}
		} else {
			$folder = explode("selected>", $pages);
			$folder = explode("</option>", $folder[1]);			
			// URL Manga
			$chapter[] = $_GET['url'];
			// Name Manga
			$chapter[] = ereg_replace('[\!\?\'\"\/\#\:]|[\.]{2,}',' ',$folder[0]);
		}
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
		$pages = file_get_contents($_GET['mangaurl']);
		$image = explode('<select id="dropdown_page"', $pages);
		$imageName = explode("<option value='", $image[1]);	
		$tmp = explode('var file_path = "',$pages);
		$source = explode('";',$tmp[1]);
		$imgName = array();		
		foreach($imageName as $i=>$name){
			if($i!=0) {
				$list = explode("'",$name);
				$imgName[$i+1] = $source[0].$list[0];
			} else {
				$imgName[0] = $_GET['manganame'];
				$imgName[1] = count($imageName);
			}
		}
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}
?>
