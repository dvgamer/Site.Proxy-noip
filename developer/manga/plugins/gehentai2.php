<?php
//$_GET['url'] = 'http://viruseddy.blogspot.com/2010/12/iris-zero-chapter-1.html';
//$_GET['mangaurl'] = 2;
//$_GET['dir'] = 'C:\AppServ\www\LifeItMy\tmp_file\[Manga] Ao no exorcist';
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
		$list = explode('<div id="gdt">', $pages);
		for($i=1;$i>=20;$i++) {
			$url = explode('<a href="', $list[$i]);
			$url = explode('"><img', $list[$i]);
			
		}
			
		$chapter[] = $_GET['url'];
		$chapter[] = $name[0];
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
		$total = explode('<p class="ip">', $pages);
		$total = explode('images', $total[1]);
		$total = explode('of', $total[0]);
		
		$page_one = explode('<div id="gdt">', $pages);
		$page_one = explode('<a href="', $page_one[1]);
		$page_one = explode('">', $page_one[1]);
		$next_page = $page_one[0];
		$imgName = array();
		$imgName[0] = $_GET['manganame'];
		$imgName[1] = (int)$total[1]+1;
		
		for($i=1;$i<=$total;$i++) {
			$page = file_get_contents($next_page);
			$image = explode('</iframe>', $page);
			$next_page = explode('<a href="', $image[1]);
			$next_page = explode('"><img src="', $next_page[1]);
			$image = explode('" style="', $next_page[1]);
			$next_page = $next_page[0];
			$imgName[$i+1] = $image[0];
		}
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}
?>
