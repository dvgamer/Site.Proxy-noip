<?php
set_time_limit(120); 
$i=1;
do {
	$browser = new COM("InternetExplorer.Application");
	$handle = $browser->HWND;
	$browser->StatusBar = false;
	$browser->Fullscreen = true;
	$browser->Visible = true;
	$browser->Navigate("http://192.168.1.9/anime/image.php?crop=".$i);
	
	while ($browser->Busy) {
		com_message_pump(20000);
	}	
	$im = imagegrabwindow($handle);
	$browser->Quit();	
	
	$filename = "tmp_".$i.".png";
	imagepng($im, $filename);
	
	list($width, $height) = getimagesize($filename);
	$new_width = $width-176;
	if ($i==1) {
		$new_height = $height;		
	} else {
		$new_height = $height-436;		
	}	
	$image_p = imagecreatetruecolor($new_width, $new_height);
	$image = imagecreatefrompng($filename);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

	imagepng($image_p, $filename);
	imagedestroy($im);

	if (filesize($filename)>102400) { $i++;	}	
} while($i<=2);

$tmp_img1 = "tmp_1.png";
$tmp_img2 = "tmp_2.png";

list($width1, $height1) = getimagesize($tmp_img1);
list($width2, $height2) = getimagesize($tmp_img2);

$imageSave = imagecreatetruecolor($width1, $height1+$height2);
$tmp1 = imagecreatefrompng($tmp_img1);
$tmp2 = imagecreatefrompng($tmp_img2);
imagecopy($imageSave, $tmp1, 0, 0, 0, 0, $width1, $height1);
imagecopy($imageSave, $tmp2, 0, $height1, 0, 0, $width2, $height2);

imagepng($imageSave, "ImageSave.png");
unlink($tmp_img1);
unlink($tmp_img2);
?> 

