<?php
class RequestPath
{
	private $isPath = array();
	private $urlPath = array();
	
	public function __construct()
	{
		$this->urlPath = explode('/', trim($_SERVER['REQUEST_URI']));	
	}
	
	public function NextRequest($req)
	{
		$pathUrl = NULL;
		switch(gettype($req))
		{
			case 'string':
				$pathUrl .= $this->EncondeName($req).'/';
				break;
			case 'array':
				foreach ($req as $list=>$value)
				{
					if (!$list || $value!=="")
					{
						$pathUrl .= $this->EncondeName($value).'/';
					}
				}
				break;
		}
		return $pathUrl;
	}
	
	public function GetRequest()
	{
		foreach($this->urlPath as $value)
		{
			if($value!="")
			{
				$tmp[] = $this->DecondeName($value);
			}
		}
		if (isset($tmp)) {
			return $tmp;
		} else {
			return array('Not Found Page.');	
		}
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
			$this->isPath[$level] = $this->EncondeName($name);
		} else {
			for($listPath=$totalReq;$listPath<=$level;$listPath++)
			{
				if($this->Level($listPath))
				{
					$this->isPath[$listPath] = $this->Level($listPath);
				} else {
					$this->isPath[$listPath] = $this->EncondeName($name);
				}
			}
		}
		
		// ViewPath
		return $this->NextRequest($this->isPath);
	}	
	
	public function Level($level)
	{
		if (isset($this->urlPath[$level])) {
			return $this->DecondeName($this->urlPath[$level]);
		} else {
			return false;
		}
	}
	
	public function TotalLevel()
	{
		return count($this->urlPath)-2;
	}
	
	protected function EncondeName($pathName)
	{
		$tmp = ereg_replace(' ', '_', trim($pathName));
		return rawurlencode($tmp);
	}	
		
	protected function DecondeName($pathName)
	{
		$tmp = ereg_replace('_', ' ', $pathName);
		return trim(rawurldecode($tmp));
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