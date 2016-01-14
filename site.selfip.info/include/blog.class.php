<?php
class Blog extends PDOConnection
{
	public function __construct()
	{		
		parent::__construct('selfip');
	}
	
	public function ViewerBlog($blog)
	{
		try	{
			$blogid = $this->GetBlogID($blog);
			$viewCurrent = parent::GetValue('view', 'blog', 'blogid', $blogid);
			$viewCurrent++;
			$sqlString = 'UPDATE '.$this->isConfig[$this->isSite]['table'].'blog SET view=:view WHERE blogid=:blogid;';
			$statement = $this->isConnect->prepare($sqlString);
			$statement = $this->bindState($statement, array('view','blogid'), array($viewCurrent, $blogid));
			$statement->execute();
		} catch(PDOException $e) {
			parent::ErrorException('CountRow', $e,$sqlString);
		}
	}
	
	public function GetBlogID($blog)
	{
		return parent::GetValue('blogid','blog', 'title', $blog);
	}
}
?>