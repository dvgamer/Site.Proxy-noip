<?php
//$_GET['url'] = 'http://www.nekopost.net/content/ao_no_exorcist';
//$_GET['mangaurl'] = 2;
//$_GET['dir'] = 'C:\AppServ\www\LifeItMy\tmp_file\[Manga] Ao no exorcist';
switch($_GET['mode'])
{	
	// Check manga Name and Total Chapter 
	case 'chk';
		$chk = explode('/',$_GET['url']);
		if(count($chk)>6) {			
			for($i=0;$i<count($chk)-1;$i++){
				$url .= $chk[$i].'/';
			}				
			$_GET['url'] = $url;
			$chapter = $chk[count($chk)-1];
			$part = 1;
		} 
			
		$pages = file_get_contents($_GET['url']);
		$name = explode('<div class="title"><h3>', $pages);
		$name = explode('</h3>', $name[1]);
		
		if(count($chk)<6) {
			$tmp = explode("<li><div class='chapter-name'>", $pages);	
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
			for($i=0;$i<count($chk)-1;$i++){
				$url .= $chk[$i].'/';
			}				
			$_GET['url'] = $url;
			$oneChapter = true;
			$listChapter = $chk[count($chk)-1];
		}
		$pages = file_get_contents($_GET['url']);
		$chapterName = explode("<div class='chapter-name'>".$listChapter, $pages);
		$chapter = array();
		foreach($chapterName as $i=>$name){
			if($i!=0) {
				$list = explode(" - ",$name);		
				$folder = explode("</div><div class='translator'>",$name);
				// URL Manga
				$chapter[] = $listChapter.$list[0];
				// Name Manga
				$chapter[] = $listChapter.ereg_replace('[\!\?\'\"\/\#\:]|[\.]{2,}',' ',$folder[0]);
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
		$pages = file_get_contents($_GET['url'].'/'.$_GET['mangaurl']);
		$image = explode("<select id=dropdown_page name=dropdown_page", $pages);
		$imageName = explode("<option value=", $image[1]);	
		$tmp = explode('document.img_content.src = "',$pages);
		$source = explode('" + dropdown_value;',$tmp[1]);
		$imgName = array();		
		foreach($imageName as $i=>$name){
			if($i!=0) {
				$list = explode(">",$name);
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
