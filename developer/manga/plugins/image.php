<?php
//$_GET['url'] = 'http://upic.me/i/ot/9mr18.png';
//$_GET['dir'] = 'C:\AppServ\www\LifeItMy\tmp_file';
//$_GET['id'] = 21;
error_reporting(0);

switch($_GET['mode'])
{
	// Write Image To Driectory
	case 'write';
	
		// Convert name For Process
		$urlImages = iconv('utf-8','tis-620',$_GET['url']);
		$urlDirectory = iconv('utf-8','tis-620',$_GET['dir']);
		$isFile = pathinfo($urlImages);	
	
		// Generator Number Files
		if ($_GET['id']=='GetName')
		{
			$nameFile = $isFile['filename'];
		} else {
			if(strlen($_GET['id'])<2) {
				$nameFile = '00'.$_GET['id'];
			} else if(strlen($_GET['id'])<3) {
				$nameFile = '0'.$_GET['id'];
			} else {
				$nameFile = $_GET['id'];
			}
		}
		
		// Target File Write
		$isSave = $urlDirectory.'\\'.$nameFile.'.'.$isFile['extension'];	
		
		//Skip Image Save Or Write Image
		if(!is_readable($isSave)) {
			$info = getimagesize($urlImages);		
			$image_p = imagecreatetruecolor($info[0], $info[1]);
			if ($info['mime']=='image/jpeg') {
				$image = imagecreatefromjpeg($urlImages);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $info[0], $info[1], $info[0], $info[1]);
				imagejpeg($image_p, $isSave,100);
			} else if ($info['mime']=='image/png') {
				$image = imagecreatefrompng($urlImages);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $info[0], $info[1], $info[0], $info[1]);
				imagepng($image_p, $isSave);
			} else if ($info['mime']=='image/gif') {
				$image = imagecreatefromgif($urlImages);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $info[0], $info[1], $info[0], $info[1]);
				imagegif($image_p, $isSave);
			}
			
		} else {
			$created = 2;
		}
		
		// Return Process Skip Or Success
		if(is_readable($isSave) && $created!=2) {
			$created = 1;
		} else if(!is_readable($isSave)) {
			$created = 0;
		}
		$name = explode("\\",$isSave);
		$hosting = parse_url($_GET['url']);
		// Json Encode
		echo json_encode(array(
					'id'=>$_GET['id'],'path'=>iconv('tis-620','utf-8',$isSave),
					'created'=>$created,
					'name'=>iconv('tis-620','utf-8',$name[count($name)-2]),
					'file'=>iconv('tis-620','utf-8',$hosting['host'].'/'.$name[count($name)-1])
			));

	break;	
}
?>