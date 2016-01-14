<?php
class DirectoryReader
{
	private $directoryName;
	public $location;
	private $listDirectory = array();
	
	public function __construct($loc)
	{
		$this->location = $loc;				
		$this->directoryName = opendir($this->location);
		while (($entry = readdir($this->directoryName)) !== false)
		{
			$this->listDirectory[] = iconv('tis-620','utf-8',$entry);
		}
	}
	
	public function toArray()
	{
		return $this->listDirectory;
	}
	
	public function __toString()
	{
		$list = NULL;
		foreach($this->listDirectory as $entry) {
			$list .= $entry."<br />\n";
		}
		return $list;
	}
	
	public function __destruct()
	{
		closedir($this->directoryName);
	}
	
	
}
?>