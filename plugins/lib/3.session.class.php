<?php
class Session
{
	public function __construct()
	{
		
	}
	
	public function isSession($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	
	public function unSession($name)
	{
		unset($_SESSION[$name]);
	}
	
	public function Cookie($name, $value, $minute)
	{
		if(!$minute) {
			setcookie($name, $value, $minute, '/');
		} else {
			setcookie($name, $value, time() + ($minute*60), '/');
		}
	}
	
	public function Value($name)
	{
		if(isset($_SESSION[$name])){
			return $_SESSION[$name];
		} elseif(isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		} else {
			return false;
		}
	}
	
	public function __destruct()
	{
		ob_end_flush();
	}
}
?>