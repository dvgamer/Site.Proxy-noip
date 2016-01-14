<?php
class Engine extends PDOConnection
{
	private $iconBox = array();
	private $Request;
	public $MangaStore = array();
	public $Domain;
	public $Document;
	
	public function __construct()
	{
		parent::__construct('hakko');
		$this->Domain = $GLOBALS['Domain'];
		$this->Document = $GLOBALS['Document'];
		$this->Request = new RequestPath();
		$this->iconBox = array("file" => "images/ico/file_ico.png","info" => "images/ico/infor_ico.png","mov" => "images/ico/movie_ico.png",
						"pic" => "images/ico/picture_ico.png","sound" => "images/ico/sound_ico.png","tag" => "images/ico/tag_ico.png");		
	}
	
	public function Navigator()
	{
		$navList = '';
		if($this->Request->Level(1)!='' && $this->Request->Level(1)!='board' && $this->Request->Level(1)!='info') {
			if($this->Request->Level($this->Request->TotalLevel())!='') {
				$navList = '<a href="'.$this->Request->SetRequest($this->Request->TotalLevel()-1,$this->Request->Level($this->Request->TotalLevel()-1)).'"><li class="back">&nbsp;</li></a>';
			}
		}
		$navList .= '<a href="'.$this->Request->SetRequest(0,'').'"><li class="home"';
		if($this->Request->Level(1)=='') { $navList .= ' id="selected"'; }
		$navList .= '>&nbsp;</li></a>';
		
		$navList .= '<a href="'.$this->Request->SetRequest(1,'board').'"><li class="board"';
		if($this->Request->Level(1)=='board') { $navList .= ' id="selected"'; }
		$navList .= '>&nbsp;</li></a>';
		
		$navList .= '<a href="'.$this->Request->SetRequest(1,'info').'"><li class="info"';
		if($this->Request->Level(1)=='info') { $navList .= ' id="selected"'; }
		$navList .= '>&nbsp;</li></a>';
 		return $navList;
	}
	
	public function BoxFull($text,$ico)
	{
		return $this->Box(1100, $text,$ico);
	}
	
	public function BoxBody($text,$ico)
	{
		return $this->Box(795, $text,$ico);
	}
	
	public function BoxMod($text,$ico)
	{
		return $this->Box(295, $text,$ico);
	}
	
	public function ThumbList($image, $name, $status, $chapter, $timeStamp)
	{
		switch($status)
		{
			case 'Active': $color = '#91cd49'; break;
			case 'Cancel': $color = '#cd4949'; break;
			case 'Drop': $color = '#4974cd'; break;			
			default: $color = '#333333'; break;			
		}

		$textCh = 'Chapter: '.$chapter;
		if((float)$chapter<1 && (float)$chapter!=0) {
			$textCh = 'Special Chapter: '.($chapter*10);
		}
		$list = '<a href="'.$this->Request->SetRequest(1,$name).'">';
		$list .= '<table width="100%" id="list-manga" border="0" cellspacing="0" cellpadding="0"><tr><td width="74" valign="top">';
		$list .= '<div id="list-thumb"><img src="'.$this->Domain.$image.'" border="0" width="64" height="64" /></div></td>';
		$list .= '<td valign="middle"><div id="list-name">'.$name.'</div><div id="list-detail">';
		$list .= $textCh.'<br/>Status: <strong><font color="'.$color.'">'.$status.'</font></strong><br/>Created: '.date('M, d Y', $timeStamp).'</div></div></td></tr></table></a>';
		return $list;
	}
	
	public function BoxManga($image)
	{
		$boxTable = '<div id="box-manga"><table class="hakko-box" border="0" cellspacing="0" cellpadding="0"><tr>';
		$boxTable .= '<td id="conner-lt"></td><td id="border-t"></td><td id="conner-rt"></td>';
		$boxTable .= '</tr><tr><td id="border-l" valign="top">&nbsp;</td><td id="body-box"><div id="image-manga">'.$image.'</div><div id="image-space">&nbsp;</div></td><td id="border-r"></td></tr><tr>';
		$boxTable .= '<td id="conner-lb"></td><td id="border-b"></td><td id="conner-rb"></td></tr></table></div>';
		return $boxTable;
	}
	
	public function Box($width,$text,$ico)
	{
		$imgIcon = '';
		if($ico) { $imgIcon = '<img src="'.$this->Domain.$this->iconBox[$ico].'" width="30" height="35" border="0" vspace="7" />'; }
		$boxTable = '<table class="hakko-box" width="'.$width.'" border="0" cellspacing="0" cellpadding="0"><tr>';
		$boxTable .= '<td id="conner-lt"></td><td id="border-t"></td><td id="conner-rt"></td>';
		$boxTable .= '</tr><tr><td id="border-l" valign="top">'.$imgIcon.'</td><td id="body-box">'.$text.'</td><td id="border-r"></td></tr><tr>';
		$boxTable .= '<td id="conner-lb"></td><td id="border-b"></td><td id="conner-rb"></td></tr></table>';
		return $boxTable;
	}
	
	public function MangaTranslator($listManga)
	{
		$validFormat = '\[Manga]|\[Cancel]|\[Download]|\[Drop]|\[Wait]';		
		foreach($listManga->toArray() as $manga) {
			if(ereg('\[Manga]',$manga))
			{
				$tmpManga = array();
				$tmpChapter = array();
				$createdManga = filectime($listManga->location.$manga);
				$nameManga = ereg_replace($validFormat,'',$manga);
				$listChapter = new DirectoryReader($listManga->location.$manga);
				$mangaThumb	= 'images/none-thumb.jpg';
				$mangaCover	= 'images/none-cover.jpg';				
				foreach($listChapter->toArray() as $chapter) {
					$utfName = iconv('utf-8','tis-620',$chapter);
					if(is_file($listChapter->location.'/'.$utfName)) {
						$chkInfo = pathinfo($chapter);				
						if($chkInfo['filename']=='cover') {
							$mangaCover	= 'translator/'.$manga.'/'.$chapter;			
						} elseif($chkInfo['filename']=='thumb') {					
							$mangaThumb	= 'translator/'.$manga.'/'.$chapter;				
						}
					}	
				}
				$tmp = explode('[',$manga);
				if(count($tmp)==3) {
					$status = explode(']',$tmp[2]);
					$status = $status[0];
				} else {
					$status = 'Active';
				}		
				$tmpManga['name'] = trim($nameManga);
				$tmpManga['status'] = trim($status);
				$tmpManga['created'] = $createdManga;
				$tmpManga['thumb'] = $mangaThumb;
				$tmpManga['cover'] = $mangaCover;
				$tmpManga['last_ch'] = 'None';	
				$tmpManga['last_id'] = 0;									
				foreach($listChapter->toArray() as $chapter) {
					$utfName = iconv('utf-8','tis-620',$chapter);
					$lastCreatedChapter = 0;
					if(is_dir($listChapter->location.'/'.$utfName) && $chapter!=='.' && $chapter!=='..') {
						$tmplist = array();
						$createdChapter = filectime($listChapter->location.'/'.$utfName);
						$tmplist['created'] = $createdChapter;	
						$tmplist['name'] = $chapter;
						$tmpChapter[] = $tmplist;
						if($createdChapter>$lastCreatedChapter) {
							$lastChapter = explode('-',$chapter);
							$tmpManga['last_ch'] = $chapter;	
							$tmpManga['last_id'] = $lastChapter[0];
						}						
					}
				}
				$tmpManga['chapter'] = $tmpChapter;
				$this->MangaStore[] = $tmpManga;			
			}
		}
	}
	
	public function InsertComment($userid, $manganame, $comment, $timestamp, $ipaddress) 
	{
		try	{
			$sqlString = 'INSERT INTO '.$this->isConfig[$this->isSite]['table'].'comment (userid, manga, comment, created, ip)';
			$sqlString .= ' VALUES (:userid, :manga, :comment, :created, :ip);';
			$statement = $this->isConnect->prepare($sqlString);
			$statement = $this->bindState($statement, array('userid','manga','comment','created','ip'), array($userid, $manganame, $comment, $timestamp, $ipaddress));
			$statement->execute();
		} catch(PDOException $e) {
			parent::ErrorException('ADD Comment', $e,$sqlString);
		}
	}
	
	public function DebugArray($list)
	{
		echo '<div align="left" style="width:500px;"><pre>';
		print_r($list);
		echo '</pre></div>';
	
	}
}	
?>