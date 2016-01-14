<?php
 $fpages = fopen($_GET['url'],'r');
 $pass = 0;$pages = 0;$start=0;
 while(!feof($fpages)){				 
	 $lines = fgets($fpages);
	 if($start==0) {
		 if(count(explode('<select name="pages"',$lines))>1) {
			$start = 1;
		 }
	 } else {
		 if(count(explode('<option value=',$lines))>1) {
			$pages++;
		 }					 
	 }
	 $chk = explode("static.bleachexile.com",$lines);
	 //echo htmlspecialchars($line).'<br>';
	 if(count($chk)>1) {
		$pass = 1;
		break;
	 }
 }
 fclose($fpages);
 $tmp = explode('static.bleachexile.com',$lines);
 $image = explode('" border="0',$tmp[1]);
 echo '<strong>Page: </strong>'.$pages.'<br>';
 echo '<strong>Image: </strong>'.$image[0];
?>