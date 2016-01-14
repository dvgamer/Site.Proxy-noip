<?php
require_once('engine.java.php');
$listManga = new DirectoryReader($HaKko->Document.'translator/');

$totalImage = 0;
$totalChapter = 0;
$indexChapter = 0;
$otherfile = 0;

$listChapter = new DirectoryReader($listManga->location.iconv('utf-8','tis-620','[Manga] ').$_POST['manga']);
foreach($listChapter->toArray() as $chapter) {
	if(is_file($listChapter->location.'/'.$chapter)) { $otherfile++; }
}
$totalChapter = count($listChapter->toArray())-2-$otherfile;
$listImage = new DirectoryReader($listChapter->location.'/'.iconv('utf-8','tis-620',$_POST['chapter']));
$totalImage = count($listImage->toArray())-1;	
$indexChapter = explode('-',iconv('utf-8','tis-620',$_POST['chapter']));
$indexChapter = trim($indexChapter[0]);

$allChapter = array();
$HaKko->MangaTranslator($listManga);
foreach($HaKko->MangaStore as $manga) {
	if($_POST['manga']==$manga['name']) {
		foreach($manga['chapter'] as $list) {
			$tmpChapter = explode('-',$list['name']);
			if(strlen((int)$tmpChapter[0])==2) {
				$tmpChapter[0] = '0'.$tmpChapter[0];
			} else if(strlen((int)$tmpChapter[0])==1) {
				$tmpChapter[0] = '00'.$tmpChapter[0];
			}
			$tmpChapter['id'] = $tmpChapter[0];
			$tmpChapter['name'] = trim($tmpChapter[1]);
			$allChapter[] = $tmpChapter;
		}
	}
}

// All Chapter List
sort($allChapter);
$foundNext = false;
$aNext = false;
$aBack = false;
foreach($allChapter as $index=>$chapter) {
	if($foundNext) {
		$aNext = (float)$chapter['id'];
		break;
	} else {
		if($index>0) {
			$aBack = (float)$allChapter[$index-1]['id'];
		}
		if((float)$chapter['id']==(float)$indexChapter) {
			$foundNext = true;
		}	
	}
}

if($totalImage!=-2) {
	echo json_encode(array('total'=>$totalImage,'anext'=>$aNext,'aback'=>$aBack));
} else {
	echo '<h1>HaKKoMEw Engine.</h1>';
}
?>