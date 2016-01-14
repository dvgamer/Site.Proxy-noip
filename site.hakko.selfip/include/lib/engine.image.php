<?php
if($_POST['id']<=$_POST['total']) {
	if($_POST['id']>=2) {
		require_once('engine.java.php');
		$dirChapter = $HaKko->Document.'translator/'.iconv('utf-8','tis-620','[Manga] ').$_POST['manga'].'/'.iconv('utf-8','tis-620',$_POST['chapter']);
		$listImage = new DirectoryReader($dirChapter);
		foreach($listImage->ToArray() as $index=>$image) {
			if(is_file($listImage->location.'/'.$image) && $_POST['id']==$index) {
				$selectedImage = $image;
			}
		}	
		$image_file = $dirChapter.'/'.$selectedImage;
		$image_url = 'translator/'.rawurlencode('[Manga] ').rawurlencode($_POST['manga']).'/'.rawurlencode($_POST['chapter']).'/'.rawurlencode($selectedImage);
		$width = getimagesize($image_file);	
		echo json_encode(array('image'=>$image_url,'width'=>$width[0],'height'=>$width[1]));
	} else {
		echo json_encode(array('image'=>'back'));
	}
} else {
	echo json_encode(array('image'=>'next'));
}
?>
