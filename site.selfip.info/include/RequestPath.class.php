<?php
class RequestPath
{
	private $isPath = array();
	private $urlPath = array();
	
	public function __construct()
	{
		$this->urlPath = explode('/', trim($_SERVER['REQUEST_URI']));	
	}
	
	public function SetRequest($level,$name)
	{
		$this->isPath = $this->urlPath;
		$totalReq = 0;
		foreach ($this->isPath as $value)
		{
			if ($value!="") { $totalReq++; }
		}
		if (!$totalReq) { $totalReq = 1; }
		if ($level<=$totalReq)
		{
			for ($listPath=$totalReq;$listPath>$level;$listPath--)
			{
				$this->isPath[$listPath] = "";
			}
			$this->isPath[$level] = $name;
		} else {
			for($listPath=$totalReq;$listPath<=$level;$listPath++)
			{
				if($this->Request($listPath))
				{
					$this->isPath[$listPath] = $this->Request($listPath);
				} else {
					$this->isPath[$listPath] = $name;
				}
			}
		}
		
		// ViewPath
		$pathUrl = "";
		foreach ($this->isPath as $list=>$value)
		{
			if (!$list || $value!=="")
			{
				$pathUrl .= $value.'/';
			}
		}
		return $pathUrl;
	}	
	
	public function IsLevel($level)
	{
		if (isset($this->urlPath[$level])) {
			return $this->urlPath[$level];
		} else {
			return false;
		}
	}
	
	public function rePath($pathName)
	{
		return ereg_replace('[^A-Za-z0-9]', '', $pathName);
	}	
	
	public function ResetRequest()
	{
		$this->isPath = $this->urlPath;
		$this->isPath = array("");
	}	
	
	private function DebugArr()
	{
		echo '<br><strong>::DedugArr::</strong><br>';
		foreach ($this->urlPath as $list=>$value)
		{
			echo ' <li>'.$list.'->'.$value.'</li>';
		}	
	}

	public function __destruct()
	{
		//$this->DebugArr();
	}
}
?>