<?php
//$_GET['url'] = 'http://www.anity.net/?manga=to-love-ru';
//$_GET['url'] = 'http://www.anity.net/read-manga/read-manga.php?manga=umisho&chapter=chapter064&page=1';
switch($_GET['mode'])
{
	// Check manga Name and Total Chapter 
	case 'chk';
		$chk = explode('=',$_GET['url']);					
		$pages = file_get_contents($_GET['url']);
				
		if(count($chk)>2)
		{
			$tmp = explode('&', $chk[2]);	
			$name = explode('<a id="manga_series"', $pages);
			$name = explode('>', $name[1]);
			$name = explode('<', $name[1]);
			//$chapter = (int)ereg_replace('[^0-9]','',$tmp[0]);
			$chapter = 1;
			$part = 1;
		} else {
			$chapter = explode('<td scope="row">Total Translate</td>', $pages);
			$chapter = explode('<td>', $chapter[1]);
			$chapter = explode('</td>', $chapter[1]);
			$chapter = (int)$chapter[0];
			
			$name = explode('<td scope="row">Name</td>', $pages);
			$name = explode('<td>', $name[1]);
			$name = explode('</td>', $name[1]);
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
		$chapter = array();
		if(count(explode('=',$_GET['url']))>2) {			
			$oneChapter = true;
			// URL Manga
			$chapter[] = $_GET['url'];
			$name = explode('<a id="manga_series"', $pages);
			$name = explode('">', $name[1]);
			$name = explode('</a>', $name[1]);
			// Name Manga
			$folder = explode('selected="selected">', $pages);
			$folder = explode('</option>', $folder[1]);
			$chapter[] = $name[0].' '.$folder[0];
		} else {
			$tmp = explode('</thead>', $pages);
			$link = explode('<td class="left" scope="row"><a href="', $tmp[2]);
			foreach($link as $i=>$url)
			{
				if($i!=0){
					$chUrl = explode('" title="', $url);
					$chapter[] = ereg_replace('&amp;','&',$chUrl[0]);
					$chName = explode('<strong class="underline">', $url);
					$chName = explode('</strong>', $chName[1]);
					$chapter[] = $chName[0];
				}
			}
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
		set_time_limit(3600);
		$pages = file_get_contents($_GET['mangaurl']);		
		$totalPage = explode('<label> of ', $pages);
		$totalPage = explode('</label>', $totalPage[1]);
		$totalPage = (int)$totalPage[0];
		
		$imgName = array();	
		$imgName[0] = $_GET['manganame'];
		$imgName[1] = ($totalPage+1);
		
		$nextPage = NULL;
		$nextPage = $_GET['mangaurl'];
		for ($i=1;$i<=$totalPage;$i++) {
			$pages = file_get_contents($nextPage);
			$isImage = explode('<a id="manga-page"', $pages);
			$isImage = explode('<img src="', $isImage[1]);
			$isImage = explode('" alt="', $isImage[1]);
			$imgName[$i+1] = ereg_replace(' ', '%20', $isImage[0]);
			
			$nextPage = explode('<a id="next" href="', $pages);
			$nextPage = explode('" title="', $nextPage[1]);
			$nextPage = ereg_replace('&amp;','&',$nextPage[0]);
		}
		
		// FileName Original
		//$imgName[$imgName[1]+2] = 'GetName';	
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}

?>
