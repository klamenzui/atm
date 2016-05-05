<?php defined('droot') OR die('No direct script access.');
class parent_db{
	public $db_id = null;
	public $conf = null;
	public $query_id = null;
	public function __construct($conf = null)
	{
		if ($conf !== NULL)
		{
			$this->connect($conf);
		}
	}
	function connect($conf = null)
	{
		if($conf == null) die('Configuration error');
		
		if(!$this->db_id = mysql_connect($conf['host'], $conf['user'], $conf['pass'])) {
			if ($conf['errors']) die('Connect to the database errors: '.mysql_error());
			else die('Connect to the database errors');
		} 

		if(!mysql_select_db($conf['name'], $this->db_id)) {
			if ($conf['errors']) die('Connect to the database errors: '.mysql_error());
			else die('Connect to the database errors');
		}
		mysql_query("SET NAMES 'utf8'"); //cp1251
		mysql_query("SET CHARACTER SET 'utf8'"); 
		$this->conf = $conf;
		return true;
	}
	
	
}
class db extends parent_db {}