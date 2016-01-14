<?php
//50 bakuman

$site = "http://www.thaimanga.net/";
$link = '45';
$name = 'to-love-ru';
$eregname = ereg_replace('\.|\-',"\\\\0",$name);
$manga = file($site."view/".$link.'/'.$name);

$lineListManga = 0;
foreach($manga as $line=>$data) {
	if(eregi("<tr class=\"tback0\">",$data)) {
		$lineListManga = $line;
		break;
	}
}
//echo htmlspecialchars($manga[110]);
$tmp = explode('<tr class="tback',$manga[$lineListManga]);
$listManga = array();
foreach($tmp as $i=>$data)
{
	$url = ereg_replace('( )',"",$data);
	$url = ereg_replace('[\.\'\"<>][^/][^(viewer)][^/][^0-9]{1,2}[^/][^('.$eregname.')][^/][^0-9]{3,6}',"",$url);
	$url = ereg_replace('">.+',"",$url);
	$url = strrev(substr(strrev($url),0,strlen($url)-1));
	$url = explode('/00/', $url);
	//echo $url[0].'<br>';
	//$list = explode('/'.$name.'/', $url[0]);
	//$list = (int)$list[count($list)-1];
	if(trim($data)!="") {
		$listManga[$i] = $url[0].'/00/';
	}
}
echo "<strong>AnimeName: </strong>".$name."<br>";
echo "<strong>Chapter: </strong>".count($listManga)."<br>";
echo "<strong>Sample link: </strong>".$listManga[1]."<br>";

?>