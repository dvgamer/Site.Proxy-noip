<?php if(!isset($_GET['list'])): ?>

<table id="list" align="center" width="339" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" id="list-anime"><div class="list-border">
        <input type="image" id="anime-get" src="<?php echo $this->domain; ?>images/btnAnime_enable.png" />
      </div></td>
    <td align="center" id="list-manga" class="list-select"><div class="list-border">
        <input type="image" id="manga-get" src="<?php echo $this->domain; ?>images/btnManga_disable.png" disabled="disabled" />
      </div></td>
    <td align="center" id="list-game"><div class="list-border">
        <input type="image" id="game-get" src="<?php echo $this->domain; ?>images/btnGame_enable.png" />
      </div></td>
  </tr>
  <tr>
    <td colspan="3"><div id="list-detail">
        <div id="preload-list" align="center"><img src="<?php echo $this->domain; ?>images/loading.gif" width="48" height="48" border="0" vspace="3" hspace="3" /> </div>
      </div></td>
  </tr>
</table>
<?php 
else: 
require('../../plugins/lib/4.directory.class.php'); 
$animeStore = new DirectoryReader("F:\\");
$torrentStore = new DirectoryReader("G:\\");

$arrList = array();

switch($_GET['list'])
{
	case 'anime';
		foreach($animeStore->toArray() as $entry)
		{
			$arrName = array();
			if (ereg('\[ThaiSubs]',$entry)){
				$tmp = ereg_replace('\[ThaiSubs]|\[OnGoing]|\[Adult]|\[Moive]|\[PMP]|\[OVA]','',$entry);
				$anime = explode('[',$tmp);
				$ova = false;$oad = false;
				if(ereg('\[OnGoing]',$entry)) {
					$type = 'OnGoing';
				} else if(ereg('\[18+]',$entry)) {
					$type = '18+';
				} else if(ereg('\[Moive]',$entry)) {
					$type = 'Moive';
				} else {
					$type = 'Complated';
				}
				$arrName['name'] = trim($anime[0]);
				$arrName['type'] = $type;
				$chapter = explode(']',trim($anime[1]));
				$arrName['chapter'] = (int)$chapter[0];
				$arrList[] = $arrName;
			}
		}
		sort($arrList);
		$listFull = '';$listMini = '';
		$folder = $_GET['img'].'widget/anime_list/thumb/';
		foreach($arrList as $anime)
		{
			$image = ereg_replace('~','',$anime['name']).'.jpg';
			$image = ereg_replace(' ','-',$image);
			$image = ereg_replace('(1st|2nd|3rd|4th)','',$image);
			$image = ereg_replace('[^A-Za-z0-9\.\(]{2,}|\'','-',$image);			
			list($name,$part) = explode('~',$anime['name']);
			$name = ereg_replace('(1st|2nd|3rd|4th)','',$name);
					
			$listMini .= '<div class="list-store" style="padding:4px;"><strong>'.$name.'</strong> <font size="1">(';
			if($anime['chapter']==0) { $listMini .= $anime['type']; } else { $listMini .= $anime['chapter']; }
			$listMini .= ')<br />'.$part.'</font></div>';
			
			if(isset($part)) { echo ''; }		
		}
	break;
	case 'manga';
		foreach($animeStore->toArray() as $entry)
		{
			$arrName = array();
			if (ereg('\[Manga]',$entry)){
				$tmp = ereg_replace('\[Manga]|\[Complated]|\[RAW]|\[OneShot]','',$entry);
				$manga = explode('[',$tmp);
				if(ereg('\[Complated]',$entry)) {
					$type = 'Complated';
				} else if(ereg('\[RAW]',$entry)) {
					$type = 'RAW';
				} else if(ereg('\[OneShot]',$entry)) {
					$type = 'OneShot';
				} else {
					$type = 'OnGoing';
				}
				$arrName['name'] = trim($manga[0]);
				$arrName['type'] = $type;		
				$chapter = explode(']',$manga[1]);
				$arrName['chapter'] = (int)$chapter[0];
				$arrList[] = $arrName;
			}
		}
		sort($arrList);
		
		$listFull = '';
		$listMini = '';
		$folder = $_GET['img'].'widget/manga_list/thumb/';
		foreach($arrList as $manga)
		{
			$image = ereg_replace(' ','-',$manga['name']).'.jpg';
			$image = ereg_replace('[^A-Za-z0-9\.\-]{1,}|\'','',$image);
			if(strlen(iconv('utf-8','tis-620',$manga['name']))>40) {
				$tmp = explode(" ",$manga['name']);
				$tmpName = '';
				$totalWord = 0;
				foreach($tmp as $word) {
					$totalWord += strlen($word);
					$tmpName .= $word.' ';
					if($totalWord>25) {
						$tmpName = trim($tmpName).'...';	
						$manga['name'] = $tmpName;					
						break;
					}
				}				
			}
			
			$listMini .= '<div class="list-store" style="padding:4px;"';
			$listMini .= '><strong>'.$manga['name'].'</strong> <font size="1">';
			if($manga['chapter']!=0) { $listMini .= '('.$manga['chapter'].')'; } else { $listMini .= '('.$manga['type'].')'; }
			$listMini .= '</font></div>';
		}
	break;
	case 'other';
		foreach($animeStore->toArray() as $entry)
		{
	  		if (!ereg('\[Manga]|\[ThaiSubs]',$entry) && ereg('\[{1,}',$entry)){
				$name = ereg_replace('\[Movie]|\[EngSubs]|\[Adult]','',$entry);
				$type = explode('[',$name);
				$type = explode(']',$type[1]);
				
				$arrName['name'] = $type[1];
				$arrName['type'] = $type[0];
				$chapter = explode(']',$manga[1]);
				$arrName['chapter'] = (int)$chapter[0];
				$arrList[] = $arrName;	
			}
		}
		
		foreach($torrentStore->toArray() as $entry)
		{
	  		if (ereg('\[RAW]|\[ThaiDubs]',$entry)){
				$name = ereg_replace('\[Movie]|\[EngSubs]|\[Adult]','',$entry);
				$type = explode('[',$name);
				$type = explode(']',$type[1]);
				
				$arrName['name'] = $type[1];
				$arrName['type'] = $type[0];
				$chapter = explode(']',$manga[1]);
				$arrName['chapter'] = (int)$chapter[0];
				$arrList[] = $arrName;
			}
		}
		sort($arrList);
		foreach($arrList as $other)
		{
			$listMini .= '<div class="list-store" style="padding:4px;"';
			$listMini .= '><strong>'.$other['name'].'</strong> <font size="1">';
			if($manga['chapter']!=0) { $listMini .= '('.$other['chapter'].')'; } else { $listMini .= '('.$other['type'].')'; }
			$listMini .= '</font></div>';
		}

	break;
}
echo json_encode(array('full'=>$listFull,'mini'=>$listMini));
endif;
?>
