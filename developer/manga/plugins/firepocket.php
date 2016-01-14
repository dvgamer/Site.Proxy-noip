<?php
//$_GET['url'] = 'http://firepocket.blogspot.com/2011/02/ore-no-imouto20.html';
//$_GET['mangaurl'] = 2;
//$_GET['dir'] = 'C:\AppServ\www\LifeItMy\tmp_file\[Manga] Ao no exorcist';
switch($_GET['mode'])
{	
	// Check manga Name and Total Chapter 
	case 'chk';			
		$pages = file_get_contents($_GET['url']);
		$name = explode("<h3 class='post-title entry-title'>", $pages);
		$name = explode('</h3>', $name[1]);
		$part = 1;
		$chapter = 1;
		if ($chapter>0) {
			echo json_encode(array('name'=>$name[0],'chapter'=>$chapter,'part'=>$part));
		} else {
			echo json_encode(array('name'=>'none','chapter'=>0));
		}
	break;	
	// Get list Manga URL
	case 'list';
		$pages = file_get_contents($_GET['url']);
		$name = explode("<h3 class='post-title entry-title'>", $pages);
		$name = explode('</h3>', $name[1]);
		$chapter[] = $_GET['url'];
		$chapter[] = ereg_replace('#','',trim($name[0]));
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
		$pages = file_get_contents($_GET['url']);
		$img = explode("<h3 class='post-title entry-title'>", $pages);
		$img = explode("<div class='post-footer'>", $img[1]);				
		$img = explode("src='", $img[0]);
		$endSing = "'";
		if(count($img)<2) { $img = explode('src="', $img[0]); $endSing = '"'; }
		$imgName = array();		
		foreach($img as $i=>$name){
			if($i!=0) {				
				$list = explode($endSing, $name);
				$imgName[$i+1] = $list[0];
			} else {
				$imgName[0] = $_GET['manganame'];
				$imgName[1] = count($img);
			}
		}
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}
?>
