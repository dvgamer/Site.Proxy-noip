<?php
//$_GET['url'] = 'http://www.anity.net/manga/nineteen-twentyone';
//$_GET['mangaurl'] = 'http://www.anity.net/manga/ability-shop/chapter001/1';
switch($_GET['mode'])
{
	// Check manga Name and Total Chapter 
	case 'chk';
		$chk = explode('/',$_GET['url']);
		if(count($chk)>5) {			
			for($i=0;$i<count($chk)-2;$i++){
				$url .= $chk[$i].'/';
			}				
			$_GET['url'] = $url;
			$chapter = $chk[count($chk)-1];
			$part = 1;
		} 
			
		$pages = file_get_contents($_GET['url']);
		$name = explode('<caption><strong>', $pages);
		$name = explode('</strong>', $name[1]);
		
		if(count($chk)<6) {
			$tmp = explode('<strong class="underline">', $pages);	
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
		$chk = explode('/',$_GET['url']);
		if(count($chk)>5) {			
			for($i=0;$i<count($chk)-2;$i++){
				$url .= $chk[$i].'/';
			}				
			$_GET['url'] = $url;
			$isChapter = $chk[count($chk)-2];
			$oneChapter = true;
		} 
		$pages = file_get_contents($_GET['url']);
		$chapterUrl = explode('<a href="'.$_GET['url'].$isChapter, $pages);
		$chapter = array();
		
		foreach($chapterUrl as $i=>$name){
			if($i!=0) {
				$list = explode('" title="',$name);		
				$folder = explode('<strong class="underline">',$name);
				// URL Manga
				$chapter[] = $_GET['url'].$isChapter.$list[0];
				// Name Manga
				$folder = explode('</strong></a></td>', $folder[1]);
				$chapter[] = $folder[0];
				
				if($oneChapter){ break; }
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
		set_time_limit(600);
		$chk = explode('/',$_GET['mangaurl']);
		if(count($chk)>5) {			
			for($i=0;$i<count($chk)-1;$i++){
				$url .= $chk[$i].'/';
			}				
			$mangaurl = $url;
		}		
		$pages = file_get_contents($_GET['mangaurl']);
		$totalImage = explode('<label> of ', $pages);
		$totalImage = explode('</label>', $totalImage[1]);
		$imgName = array();	
		$imgName[0] = $_GET['manganame'];
		$imgName[1] = (int)$totalImage[0]+1;
		
		for($i=1;$i<=$totalImage[0];$i++)
		{
			$manga = file_get_contents($mangaurl.$i);
			$image = explode('"><img src="', $manga);
			$image = explode('" alt="', $image[2]);	
			$imgName[$i+1] = ereg_replace(' ','%20', $image[0]);
		}
		// FileName Original
		//$imgName[$imgName[1]+2] = 'GetName';	
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}

?>
