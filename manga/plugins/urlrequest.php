<?php
//http://unixmanga.com/onlinereading/Kuroko_no_Basket.html
//http://www.manga.bleachexile.com/bakuman-chapter-1-page-1.html
//$_GET['url'] = 'http://manga.bleachexile.com/mp0-chapter-1.html';
if(isset($_GET['url'])) {	
	$tmp = parse_url($_GET['url']);
	switch($tmp['host'])
	{
		case 'www.anity.net':
			echo json_encode(array('site'=>'anity'));
			break;
		case 'www.nekopost.net':
			echo json_encode(array('site'=>'nekopost'));
			break;		
		case 'www.niceoppai.net':
			echo json_encode(array('site'=>'niceoppai'));
			break;
		case 'www.zoisandsook.com':
			echo json_encode(array('site'=>'zoisandsook'));
			break;			
		case 'board.naruto.in.th':
			echo json_encode(array('site'=>'naruto'));
			break;
		case 'thaimangaclub.blogspot.com':
			echo json_encode(array('site'=>'thaimangaclub'));
			break;
		case 'www.cartooniverse.co.uk':
			echo json_encode(array('site'=>'cartooniverse'));
			break;
		case 'manga.bleachexile.com':
			echo json_encode(array('site'=>'bleachexile'));
			break;
		case 'unixmanga.com':
			echo json_encode(array('site'=>'unixmanga'));
			break;
		default:
			echo json_encode(array('site'=>'none'));
			break;
	}	
} 
?>