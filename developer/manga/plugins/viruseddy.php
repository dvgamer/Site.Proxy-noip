<?php
//$_GET['url'] = 'http://viruseddy.blogspot.com/2010/12/iris-zero-chapter-1.html';
//$_GET['mangaurl'] = 2;
//$_GET['dir'] = 'C:\AppServ\www\LifeItMy\tmp_file\[Manga] Ao no exorcist';
switch($_GET['mode'])
{	
	// Check manga Name and Total Chapter 
	case 'chk';			
		$pages = file_get_contents($_GET['url']);
		$name = explode("<a href='".$_GET['url']."'>", $pages);
		$name = explode('</a>', $name[1]);
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
		$name = explode("<a href='".$_GET['url']."'>", $pages);
		$name = explode('</a>', $name[1]);
		$chapter[] = $_GET['url'];
		$chapter[] = ereg_replace('\?','',trim($name[0]));
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
		$img = explode("<a href='".$_GET['url']."'>", $pages);
		$img = explode($_GET['url'], $img[1]);
		$img = explode('<img src="', $img[0]);
		$imgName = array();		
		foreach($img as $i=>$name){
			if($i!=0) {
				$list = explode('"', $name);
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
