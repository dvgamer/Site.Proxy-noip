<?php
class PDOConnection
{
	protected $isSite;
	protected $isConnect;
	protected $isConfig;
	
	public function __construct($site)
	{
		$this->isSite = $site;
		$this->isConfig = @parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/config/default.Site.ini', true);;
		try	{
			if(isset($this->isConfig[$site]['name'])) {
				$dsnString = 'mysql:host='.$this->isConfig['site']['host'].';dbname='.$this->isConfig[$site]['name'].';';
			}
			$this->isConnect = new PDO($dsnString, $this->isConfig['db']['username'], $this->isConfig['db']['password']);
			$this->isConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			$this->isConnect->query("SET NAMES '".$this->isConfig['db']['set']."'");
		} catch(PDOException $excep) {
			echo '<div id="exception"><strong>DSN : </strong><font size="2">'.$dsnString.'</font><br>';
			echo '<strong>Error : </strong><br /><font size="2">'.$excep->getMessage().'</font></div>';
		}		
	}
	
	protected function SQLString($select, $table, $column, $order, $limit)
	{
		if(!$select) {	
			$sqlString = 'SELECT * FROM '.$this->isConfig[$this->isSite]['table'].$table;
		} else {
			$sqlString = 'SELECT '.$select.' FROM '.$this->isConfig[$this->isSite]['table'].$table;
		}
		switch(gettype($column))
		{
			case 'string':
				$sqlString .= ' WHERE '.$column.'=:'.$column;
				break;
			case 'array':
				$sqlString .= ' WHERE ';
				for($i=0;$i<count($column);$i++)
				{
					$sqlString .= $column[$i].'=:'.$column[$i];
					if(count($column)-1!=$i) { $sqlString .= ' AND '; }
				}
				break;
		}
				
		if($order) { $sqlString .= ' ORDER BY '.$order; }		
		if($limit) { $sqlString .= ' LIMIT '.$limit; }		
		return $sqlString.';';
	}
	
	protected function bindState($state, $column, $value)
	{
		switch(gettype($column))
		{
			case 'string':
				switch(gettype($value))
				{
					case 'string':
						$state->bindParam(':'.$column, $value, PDO::PARAM_STR);
					break;
					case 'integer':
						$state->bindParam(':'.$column, $value, PDO::PARAM_INT);
					break;
				}
			break;
			case 'array':
				for($i=0;$i<count($column);$i++)
				{
					switch(gettype($value[$i]))
					{
						case 'string':
							$state->bindParam(':'.$column[$i], $value[$i], PDO::PARAM_STR);
						break;
						case 'integer':
							$state->bindParam(':'.$column[$i], $value[$i], PDO::PARAM_INT);
						break;
					}
				}
			break;
		}
		return $state;
	}
	
	public function ViewTable($table, $limit)
	{		
		try	{	
			$sqlString = $this->SQLString(0,$table,0,0,$limit);
			$statement = $this->isConnect->prepare($sqlString);
			$statement->bindParam(':tmp', $value);
			$statement->execute();
			$rowArr = array();
			while($row = $statement->fetch(PDO::FETCH_ASSOC)) { $rowArr[] = $row;	}
			return $rowArr;
		} catch(PDOException $e) {
			$this->ErrorException('ViewTable', $e,$sqlString);
		}
	}
	
	public function ViewTableWhere($table, $column, $value, $order, $limit)
	{		
		try	{	
			$sqlString = $this->SQLString(0,$table,$column,$order,$limit);
			$statement = $this->isConnect->prepare($sqlString);
			$statement = $this->bindState($statement, $column, $value);
			$statement->execute();	
			$rowArr = array();
			while($row = $statement->fetch(PDO::FETCH_ASSOC)) { $rowArr[] = $row; }
			return $rowArr;	
		} catch(PDOException $e) {
			$this->ErrorException('ViewTableWhere',$e,$sqlString);
		}
	}

	public function GetValue($name, $table, $column, $value)
	{
		try	{
			$sqlString = $this->SQLString($name, $table, $column, 0, 1);
			$statement = $this->isConnect->prepare($sqlString);			
			$statement = $this->bindState($statement, $column, $value);
			$statement->execute();
			$row = $statement->fetch(PDO::FETCH_ASSOC);
			if($row) {
				return $row[$name];
			} else {
				return $value;
			}
		} catch(PDOException $e) {
			$this->ErrorException('GetValue', $e,$sqlString);
		}
	}

	public function CountRow($table, $column, $value)
	{
		try	{
			$sqlString = $this->SQLString('COUNT(*)', $table, $column, 0, 0, 0);
			$statement = $this->isConnect->prepare($sqlString);
			$statement = $this->bindState($statement, $column, $value);
			$statement->execute();
			return $statement->fetchColumn();
		} catch(PDOException $e) {
			$this->ErrorException('CountRow', $e,$sqlString);
		}
	}
	
	public function ErrorException($name, $e,$sql)
	{
		echo '<div id="exception">';
		echo '<strong>'.$name.' : </strong><font size="2">'.$sql.'</font><br />';
		echo '<strong>File : </strong><font size="2">'.$e->getFile().'</font><br />';
		echo '<strong>Error :</strong><br /><font size="2">'.$e->getMessage().'</font></div>';
	}

}
?>