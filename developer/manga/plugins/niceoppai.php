<?php
//$_GET['url'] = 'http://www.niceoppai.net/viewforum.php?f=10&start=0';
//$_GET['mangaurl'] = 'http://www.niceoppai.net/viewtopic.php?f=10&t=2110';
switch($_GET['mode'])
{
	// Check manga Name and Total Chapter 
	case 'chk';
		$name = 'NicePaiking';	
		$tmp = explode('&',$_GET['url']);
		$from = parse_url($tmp[0]);
		$viewforum = '/viewtopic.php?'.$from['query'];
		$tmp =  explode('=',$tmp[1]); 
		$pages = file_get_contents($_GET['url']);	
		if($tmp[0]==='start') {
			$trim = explode('<b class="gensmall">Topics</b>', $pages);
			$list = explode('class="topictitle">', $trim[1]);
			$chapter = count($list);
		} else if($tmp[0]==='t') {
			$trim = explode('<td class="gensmall" width="100%"><div style="float: left;">&nbsp;<b>Post subject:</b>', $pages);
			$name = explode('</div><div style=', $trim[1]);
			$chapter = 1;
			$part = 1;
			$name = $name[0];
		} else {
			$list = explode('class="topictitle">', $pages);	
			$chapter = 0;	
			for($i=1;$i<count($list);$i++) {			
				$chapter++;
				if (count(explode('<b class="gensmall">Topics</b>',$list[$i]))>1){
					break;
				}
			}
		}
		if ($chapter>0) {
			echo json_encode(array('name'=>$name,'chapter'=>$chapter,'part'=>$part));
		} else {
			echo json_encode(array('name'=>'none','chapter'=>0));
		}
	break;	
	// Get list Manga URL
	case 'list';
		$tmp = explode('&',$_GET['url']);
		$from = parse_url($tmp[0]);
		$viewforum = '/viewtopic.php?'.$from['query'];
		$tmp =  explode('=',$tmp[1]); 
		$pages = file_get_contents($_GET['url']);
		$chapter = array();	
		if($tmp[0]=='start') {
			$trim = explode('<b class="gensmall">Topics</b>', $pages);
			$list = explode('" class="topictitle">', $trim[1]);
			for($i=1;$i<count($list);$i++) {
				$name = explode('</a>',$list[$i]);
				$tmp = explode($viewforum, $list[$i-1]);
				$link = explode('&amp;sid=',$tmp[count($tmp)-1]);
				$name = ereg_replace('[\!\?\'\"\/\#\:]|[\.]{2,}','',$name[0]);
				$chapter[] = ereg_replace('\&amp;','&','http://www.niceoppai.net'.$viewforum.$link[0]);
				$chapter[] = ereg_replace('\&amp;','&',trim($name));
			}
		} else if($tmp[0]=='t') {
			$trim = explode('<td class="gensmall" width="100%"><div style="float: left;">&nbsp;<b>Post subject:</b>', $pages);
			$name = explode('</div><div style=', $trim[1]);
			$name = ereg_replace('[\!\?\'\"\/\#\:]|[\.]{2,}','',$name[0]);
			$chapter[] = $_GET['url'];			
			$chapter[] = ereg_replace('\&amp;','&',trim($name));
		}else {
			$list = explode('class="topictitle">', $pages);		
			for($i=1;$i<count($list);$i++) {			
				$name = explode('</a>',$list[$i]);
				$tmp = explode($viewforum, $list[$i-1]);
				$link = explode('&amp;sid=',$tmp[count($tmp)-1]);
				$name = ereg_replace('[\!\?\'\"\/\#\:]|[\.]{2,}','',$name[0]);
				$chapter[] = ereg_replace('\&amp;','&','http://www.niceoppai.net'.$viewforum.$link[0]);
				$chapter[] = ereg_replace('\&amp;','&',trim($name));
				if (count(explode('<b class="gensmall">Topics</b>',$list[$i]))>1){
					break;
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
		$pages = file_get_contents($_GET['mangaurl']);
		$tmp = explode('<div class="postbody">', $pages);
		$image = explode('<table width="100%" cellspacing="0">', $tmp[1]);
		$imagelist = explode('<img src="', $image[0]);
		$imgName = array();	
		$imgName[0] = $_GET['manganame'];
		$imgName[1] = (int)count($imagelist);
		for($i=1;$i<$imgName[1];$i++)
		{
			$image = explode('" alt="', $imagelist[$i]);
			$isImage = pathinfo($image[0]);	
			$isUrl = parse_url($isImage['dirname']);
			switch($isUrl['host'])
			{
				case 'upic.me':
					$isUrl['host']='th.upic.me';
					$image = $isUrl['scheme'].'://'.$isUrl['host'].$isUrl['path'].'/'.$isImage['basename'];
				break;
				default:
					$image = $image[0];
				break;			
				
			}
			$imgName[$i+1] = $image;
		}
		// FileName Original
		//$imgName[$imgName[1]+2] = 'GetName';	
		echo json_encode($imgName);	
	break;
	default:

	break;
		
}

?>
