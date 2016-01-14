<?php
class BBCode
{
	protected $searchSimple;
	protected $replaceSimple;
	protected $path;
	
	public function __construct()
	{
		$this->path = new RequestPath();		
		$this->searchSimple = array(  
				//added line break  
				'/\[br]/is',  
				'/\[b\](.*?)\[\/b\]/is',  
				'/\[i\](.*?)\[\/i\]/is',  
				'/\[u\](.*?)\[\/u\]/is',  
				'/\[a\=(.*?)\](.*?)\[\/a\]/is',  
				'/\[url\=(.*?)\](.*?)\[\/url\]/is',  
				'/\[url\](.*?)\[\/url\]/is',  
				'/\[align\=(left|center|right)\](.*?)\[\/align\]/is',  
				'/\[img\](.*?)\[\/img\]/is',  
				'/\[img\=(.*?)\](.*?)\[\/img\]/is',  
				'/\[mail\=(.*?)\](.*?)\[\/mail\]/is',  
				'/\[mail\](.*?)\[\/mail\]/is',  
				'/\[font\=(.*?)\](.*?)\[\/font\]/is',  
				'/\[size\=(.*?)\](.*?)\[\/size\]/is',  
				'/\[color\=(.*?)\](.*?)\[\/color\]/is',  
				//added pre class for code presentation  
				'/\[code\](.*?)\[\/code\]/is',  
				//added paragraph  
				'/\[p\](.*?)\[\/p\]/is',  
				'/\[p\=(.*?)\](.*?)\[\/p\]/is', 
				'/\[center\](.*?)\[\/center\]/is', 
				//Orderlist				
				'/\[li\](.*?)\[\/li\]/is',  
				//Download				
				'/\[item=(.*?)\](.*?)\[\/item\]/is',  
				'/\[clip\](.*?)\[\/clip\]/is',  
			);  
	  
		$this->replaceSimple = array(  
				//added line break  
				'<br />',  
				'<strong>$1</strong>',  
				'<em>$1</em>',  
				'<u>$1</u>',  
				// added nofollow to prevent spam  
				'<a href="$1" rel="nofollow">$2</a>',  
				'<a href="$1" target="_blank" rel="nofollow" title="$2 - $1">$2</a>',  
				'<a href="$1" target="_blank" rel="nofollow" title="$1">$1</a>',  
				'<div style="text-align: $1;">$2</div>',  
				//added textarea for code presentation  
				'<div class="img-blog"><img src="'.$GLOBALS['Domain'].'module/blog/$1" border="0" /></div>',  
				'<div class="img-blog"><img src="'.$GLOBALS['Domain'].'module/blog/$2" border="0" /><div class="img-text">$1</div></div>',  
				//added alt attribute for validation  
				'<a href="mailto:$1">$2</a>',  
				'<a href="mailto:$1">$1</a>',  
				'<span style="font-family: $1;">$2</span>',  
				'<span style="font-size: $1;">$2</span>',  
				'<span style="color: $1;">$2</span>',  
				//added pre class for code presentation  
				'<div class="code">$1</div>',  
				//added paragraph  
				'<p><span style="margin-left:15px;">$1</span></p>',  
				'<p align="$1"><span style="margin-left:15px;">$2</span></p>', 
				'<center>$1</center>', 
				//Orderlist				
				'<li>$1</li>',  
				//Download				
				'<div class="download"><strong>Download: </strong><a href="'.$this->path->SetRequest(1, 'download').'$2">$1</a></div>',  
				'<center><div class="facebook-video"><object width="716" height="401"><param name="allowfullscreen" value="true" /><param name="movie" value="http://www.facebook.com/v/$1" />
				<embed src="http://www.facebook.com/v/$1" type="application/x-shockwave-flash" allowfullscreen="true" width="716" height="401"></embed></object></div></center>',  
				
			); 
	}

	public function Format($string) {
		
		$string = htmlentities($string, ENT_NOQUOTES, 'UTF-8'); 				
		// Do simple BBCode's  
		$string = preg_replace($this->searchSimple, $this->replaceSimple, $string);  	  
		// Do <blockquote> BBCode  
		$string = $this->Quote($string);
		
		return $string;  
	}  
		
	private function Quote($string)
	{  
		//added div and class for quotes  
		$open = '<blockquote><div class="quote">';  
		$close = '</div></blockquote>';  
	  
		// How often is the open tag?  
		preg_match_all ('/\[quote\]/i', $string, $matches);  
		$opentags = count($matches['0']);  
	  
		// How often is the close tag?  
		preg_match_all ('/\[\/quote\]/i', $string, $matches);  
		$closetags = count($matches['0']);  
	  
		// Check how many tags have been unclosed  
		// And add the unclosing tag at the end of the message  
		$unclosed = $opentags - $closetags;  
		for ($i = 0; $i < $unclosed; $i++) {  
			$str .= '</div></blockquote>';  
		}  
	  
		// Do replacement  
		$string = str_replace ('[' . 'quote]', $open, $string);  
		$string = str_replace ('[/' . 'quote]', $close, $string);  
	  
		return $string;  
	}  
}

class BB
{
	public static function Code($string)
	{
		$bb = new BBCode();
		return $bb->Format($string);
	}
	
	public static function Date($timestamp,$type)
	{
		$listFullMonth = array(
				"Jan"=>_January,
				"Feb"=>_February,
				"Mar"=>_March,
				"Apr"=>_April,
				"May"=>_Mays,
				"Jun"=>_June,
				"Jul"=>_July,
				"Aug"=>_August,
				"Sep"=>_September,
				"Oct"=>_October,
				"Nov"=>_November,
				"Dec"=>_December,
				);
		$listShotMonth = array(
				"Jan"=>_Jan,
				"Feb"=>_Feb,
				"Mar"=>_Mar,
				"Apr"=>_Apr,
				"May"=>_May,
				"Jun"=>_Jun,
				"Jul"=>_Jul,
				"Aug"=>_Aug,
				"Sep"=>_Sep,
				"Oct"=>_Oct,
				"Nov"=>_Nov,
				"Dec"=>_Dec,
				);

		$isDay = date('d',$timestamp);
		$isMonth = date('M',$timestamp);
		$isYear = date('Y',$timestamp)+543;
		if($type=='s') {
			$dateString = $listShotMonth[$isMonth].', '.$isDay.' '.$isYear;			
		} elseif($type=='m') {
			$dateString = $isDay.' '.$listFullMonth[$isMonth].' '.$isYear;			
		} elseif($type=='d') {
			$timestamp = $_SERVER['REQUEST_TIME'] - $timestamp;
			$day = (int)($timestamp / 86400);
			$hour = (int)((int)(($timestamp / 86400 - $day) * 10) * 2.4);
			if($day>2) {
				$dateString = _MANGA_ON.$day._MANGA_LASTDAY;
			} else {
				$dateString = _MANGA_TODAY;
			}
			
		} elseif($type=='f') {
			
		}
		return $dateString;
	}
}

?>
