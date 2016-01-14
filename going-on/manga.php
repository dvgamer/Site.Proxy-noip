<?php
$location = "F:\\";
$goingOnValid = '\[Manga]';
$dir = opendir($location);
$animeGoingOn = array();

while ($entry = readdir($dir))
{
	if(is_dir($location.$entry))
	{
		if(eregi($goingOnValid,$entry))
		{
			$tmp = explode('[Manga]',$entry);
			if(substr($tmp[1],1,1)!=='['){			
				$gen = explode('[', $tmp[1]);				
				$list = explode(']', $gen[1]);
				$anime = array();
				$anime['episode'] = $list[0];
				$anime['name'] = $gen[0];
				$animeGoingOn[] = $anime;
			} 
		}
	}
}

$txtNotes = 'Manga Translate';
$txtAnimeFile = "C:\Users\HaKko\Documents\Rainmeter\Skins\Gnometer\Notes\Manga\Manga.txt";
if(file_exists($txtAnimeFile))
{
	$isFile = fopen($txtAnimeFile, 'w');
	fputs($isFile, '<title>'.$txtNotes."</title>\r\n\r\n<notes>");
	foreach($animeGoingOn as $line=>$anime)
	{
		if (strlen($anime['episode'])==3 && trim($anime['name'])!=="Complated") {
			echo '<strong>'.$anime['episode'].'</strong> '.$anime['name'].'<br>';
			fputs($isFile, $anime['episode'].' '.$anime['name']."\r\n");
		} else if (strlen($anime['episode'])==2 && trim($anime['name'])!=="Complated") {
			echo '&nbsp;&nbsp;<strong>'.$anime['episode'].'</strong> '.$anime['name'].'<br>';
			fputs($isFile, " "." ".$anime['episode'].' '.$anime['name']."\r\n");
		}
	}
	fputs($isFile, '</notes>');
	fclose($isFile);
}
?>