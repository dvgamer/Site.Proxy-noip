<?php
$location = "F:\\";
$goingOnValid = "\[Going-On]";
$dir = opendir($location);
$animeGoingOn = array();

while ($entry = readdir($dir))
{
	if(is_dir($location.$entry))
	{
		if(eregi($goingOnValid,$entry))
		{			
			$name = ereg_replace('(\[ThaiSubs] \[Going-On])|[^A-Za-z[:space:]\-]|[0-9]','',$entry);
			$list = ereg_replace('[^0-9]','',$entry);
			$anime = array();
			$anime['episode'] = $list;
			$anime['name'] = $name;
			$animeGoingOn[] = $anime;
		}
	}
}

$txtNotes = 'Anime Going-On';
$txtAnimeFile = "C:\Users\HaKko\Documents\Rainmeter\Skins\Gnometer\Notes\AnimeGoing\AnimeGoing.txt";
if(file_exists($txtAnimeFile))
{
	$isFile = fopen($txtAnimeFile, 'w');
	fputs($isFile, '<title>'.$txtNotes."</title>\r\n\r\n<notes>");
	foreach($animeGoingOn as $anime)
	{
		if (strlen($anime['episode'])==3) {
			echo '<strong>'.$anime['episode'].'</strong> '.$anime['name'].'<br>';
			fputs($isFile, $anime['episode'].' '.$anime['name']."\r\n");
		}
	}
	foreach($animeGoingOn as $anime)
	{
		if (strlen($anime['episode'])==2) {
			echo '&nbsp;&nbsp;<strong>'.$anime['episode'].'</strong> '.$anime['name'].'<br>';
			fputs($isFile, " "." ".$anime['episode'].' '.$anime['name']."\r\n");
		}
	}
	fputs($isFile, '</notes>');
	fclose($isFile);
}
?>