<?php
//$_GET['dir'] = 'C:\AppServ\www\LifeItMy\tmp_file\\';
$_GET['url'] = 'http://board.naruto.in.th/forum-119-2.html';

//$_GET['url'] = 'http://www.zoisandsook.com/viewtopic.php?id=9219';
//$_GET['mangaurl'] = 'http://www.zoisandsook.com/viewtopic.php?id=11689';
switch($_GET['mode'])
{
	// Check manga Name and Total Chapter 
	case 'chk';
		$tmp = explode('/',$_GET['url']);
		$tmp = explode('-',$tmp[count($tmp)-1]);
		$pages = file_get_contents($_GET['url']);	
		if($tmp[0]==='forumdisplay' || $tmp[0]==='forum') {
			$name = explode('<div id="forumheader" class="s_clear">', $pages);	
			$name = explode('<h1>', $name[1]);	
			$name = explode('</h1>', $name[1]);			
			$list = explode('viewthread.php?tid=', $pages);
			$chapter = 0;
			foreach($list as $link)
			{
				echo $link.'<br><br>';
				$manga = explode('</a></span>',$link);
				if(isset($manga[1])){
					$chapter++;
				}
			}			
		} else if($tmp[0]==='thread') {
			$trim = explode('src="http://pagead2.googlesyndication.com/pagead/show_ads.js">', $pages);
			$name = explode('<h3>', $trim[1]);
			$name = explode('</h3>', $name[1]);
			$chapter = 1;
			$part = 1;
			$name = $name[0];
		}		
		if ($chapter>0) {
			echo json_encode(array('name'=>$name[0],'chapter'=>$chapter,'part'=>$part));
		} else {
			echo json_encode(array('name'=>'none','chapter'=>0));
		}
	break;	
	// Get list Manga URL
	case 'list';
		$chapter = array();			
		$tmp = explode('/',$_GET['url']);
		$tmp = explode('.',$tmp[count($tmp)-1]);
		$pages = file_get_contents($_GET['url']);	
		if($tmp[0]==='viewforum') {
			$trim = explode('<div id="vf" class="blocktable">', $pages);	
			$name = explode('<h2><span>', $trim[2]);	
			$name = explode('</span></h2>', $name[1]);
			$list = explode('<a href="viewtopic.php?id=', $trim[2]);
			$url = explode('viewforum',$_GET['url']);			
			for($i=1;$i<count($list);$i++) {
				$id = explode('">',$list[$i]);
				$name = explode('</a>',$id[1]);
				$chapter[] = $url[0].'viewtopic.php?id='.$id[0];
				$chapter[] = ereg_replace('\&quot;','.',ereg_replace('[\!\?\'\"\/\#\:]','',$name[0]));
			}
			
		} else if($tmp[0]==='viewtopic') {	
			$trim = explode('src="http://pagead2.googlesyndication.com/pagead/show_ads.js">', $pages);
			$name = explode('<h3>', $trim[1]);
			$name = explode('</h3>', $name[1]);
			$chapter[] = $_GET['url'];
			$chapter[] = ereg_replace('\&quot;','.',ereg_replace('[\!\?\'\"\/\#\:]','',$name[0]));
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
		$trim = explode('src="http://pagead2.googlesyndication.com/pagead/show_ads.js">', $pages);
		$tmp = explode('<div class="adminmsg">', $trim[1]);
		$image = explode('<div class="postsignature">', $tmp[1]);
		if(count($tmp)==1) {
			$tmp = explode('<div class="postmsg">', $trim[1]);	
			$image = explode('<div class="postfootleft">', $tmp[1]);					
		}
		$imagelist = explode('src="', $image[0]);
		$imgName = array();	
		$imgName[0] = $_GET['manganame'];
		$imgName[1] = (int)count($imagelist);
		for($i=1;$i<$imgName[1];$i++)
		{
			$image = explode('"', $imagelist[$i]);

			$imgName[$i+1] = $image[0];
		}
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}

?>
