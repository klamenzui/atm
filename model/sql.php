<?php defined('droot') OR die('No direct script access.');

class parent_sql{
	public $db_id = null;
	public $conf = null;
	public $q_res = null;
	public static function q($sql = NULL, $checkvars = false){
		return new sql($sql, $checkvars);
	}
	public static function valid($sql = NULL,$vars = array()){
		if ($sql !== NULL)
		{
			$rsql=$sql;
			if(is_array($vars))
			foreach($vars as $k=>$v){
				$vt=explode(':',$k);
				if(preg_match('/^[@_A-z]+[0-9@_A-z]*$/', $vt[0])){
					$vt[0]="@{$vt[0]}@";
					$t=isset($vt[1])?$vt[1]:'s';
					switch($t){
						case'i':
							if(ctype_digit($v))
								$rsql=str_replace($vt[0],$v, $rsql);
							else return array('q'=>$rsql,'e'=>"'<b>$v</b>' is not integer value in '{$vt[0]}'");
						break;
						case'f':
							if(preg_match('/^[+-]?[0-9]+[\.,]?[0-9]+$/i', $v))
								$rsql=str_replace($vt[0],(str_replace(',','.',$v)+0), $rsql);
							else return array('q'=>$rsql,'e'=>"'<b>$v</b>' is not float value in '{$vt[0]}'");
						break;
						case's':
							$rsql=str_replace($vt[0],mysql_real_escape_string($v), $rsql);
						break;
					}
				} else return array('q'=>$rsql,'e'=>"incorrect variable name '<b>{$vt[0]}</b>'");
				
			}
		}else return array('q'=>$rsql,'e'=>"empty query");
		return array('q'=>$rsql,'e'=>'');
	}
	/*
	//var_dump(sql::valid("select * from reports where id=?i[5] and fff=?f[$fff] and sss=?s[$sss];"));
	public static function valid($sql = NULL)
	{
		if ($sql !== NULL)
		{
			preg_match_all('/\?([isf])\[(.*?)\]/i', $sql, $arr, PREG_PATTERN_ORDER);
			print_r($arr);
			if(isset($arr[1],$arr[2])and (($c=count($arr[1]))==count($arr[2]))){
				$rsql=$sql;
				foreach($arr[1] as $k=>$v){
					if($v=='i'){
						if(ctype_digit($arr[2][$k]))
							$rsql=str_replace($arr[0][$k],$arr[2][$k], $rsql);
						else return false;
					}elseif($v=='f'){
						if(preg_match('/^[+-]?[0-9]+[\.,]?[0-9]+$/i', $arr[2][$k]))
							$rsql=str_replace($arr[0][$k],"'".(str_replace(',','.',$arr[2][$k])+0)."'", $rsql);
						else return false;						
					}else{
						$rsql=str_replace($arr[0][$k], "'".mysql_real_escape_string($arr[2][$k])."'", $rsql);
					}
				}
			}else return false;
		}else return false;
		return $rsql;
	}*/
	public function __construct($sql = NULL, $checkvars = false,$db_id='')
	{
		if ($sql !== NULL)
		{
			if(is_array($checkvars)){
				$r=$this->valid($sql,$checkvars);
				//print_r($sql);
				//print_r($checkvars);
				file_put_contents(droot.'logs'.ds.'sql.log',print_r($sql,true)."\n".print_r($checkvars,true));
				if(!empty($r['e']))die($r['e']);
				else $sql=$r['q'];
			}				
			$this->query($sql,$db_id);
		}
	}
	public function query($sql,$db_id='')
	{
		global $db;
		$this->_sql = $sql;
		if($db_id!='')
			$this->q_res = mysql_query($this->_sql,$db_id);
		elseif(is_object($db))
			$this->q_res = mysql_query($this->_sql,$db->db_id);
		else$this->q_res = mysql_query($this->_sql);
		if ($this->q_res===false){
		die("Invalid query: [".$this->_sql."] " . mysql_error());
		//$_SESSION['DEBUG_RESLA']['mysql_query_desc'][] = $q.' [ERROR] '.mysql_error();
		}
		else {
		//$_SESSION['DEBUG_RESLA']['mysql_query_desc'][] = $q;		
		}
		return $this;
	}
	public function row($q_res = '',$type=MYSQL_ASSOC)//MYSQL_NUM MYSQL_BOTH
	{
		if ($q_res == '') $q_res = $this->q_res;
		return mysql_fetch_array($q_res,$type);
	}
	public function rows($q_res = '',$index='',$type=MYSQL_ASSOC)//MYSQL_NUM MYSQL_BOTH
	{
		if ($q_res == '') $q_res = $this->q_res;
		$arr=array();
		if($index!='' and is_string($index))
		while ($line = mysql_fetch_array($q_res, $type))
			$arr[$line[$index]]=$line;
		else
		while ($line = mysql_fetch_array($q_res, $type))
			$arr[]=$line;
		return $arr;
	}
	public static function get($sql,$checkvars = false,$index='',$type=MYSQL_ASSOC)
	{
		$q = new sql($sql,$checkvars);
		$r=$q->q_res;
		$arr=array();
		if($index!='' and is_string($index))
		while ($line = mysql_fetch_array($r, $type))
			$arr[$line[$index]]=$line;
		else
		while ($line = mysql_fetch_array($r, $type))
			$arr[]=$line;
		return $arr;
	}
	//---- insert 2 db from array ----
	public static function add($table='',$data=array(),$unset=array())
	{ 
		return sql::ins($table,$data,$unset);
	}
	public static function ins($table='',$data=array(),$unset=array())
	{ 
		
		if(count($data)>0 and $table!=''){
			$keys=array();$vals=array();
			$b=false;$c=0;
			if(is_string($unset)and $unset!='')
				$unset=array($unset);
			$u=count($unset)>0;
			foreach($data as $key=>$values)
			if(is_array($values)){
				$b=true;$c++;
				$vals=array();				
				foreach($values as $key=>$val)
				if(($u?!(in_array($key,$unset)):true)){
					$keys[$key]="`".($key)."`";
					$vals[$key]="'".(mysql_real_escape_string($val))."'";
				}
				$v[]="(".(implode(',',$vals)).")";
			}elseif(($u?!(in_array($key,$unset)):true)){
				$keys[$key]="`".($key)."`";
				$vals[]="'".(mysql_real_escape_string($values))."'";
			}
			if(!$b){
				$values="(".(implode(',',$vals)).")";
				$c=1;
			}else $values=implode(',',$v);
			$sql = "INSERT INTO `$table`(".(implode(',',$keys)).") VALUES $values;";
			//echo"$sql<br>";
				//"ON DUPLICATE KEY UPDATE `field_def`='".(mysql_real_escape_string($field_def))."' ";
			$q = new sql($sql);
			$r=$q->q_res;
			if($r){
				$r=array('start'=>mysql_insert_id(),'count'=>$c);
				$r[0]=$r['start'];$r[1]=$r['count'];
			}
			return $r;
		}else return false;
	}
	public static function up($table='',$data=array(),$where=array(),$escapestr=true)
	{
		if(count($data)>0 and $table!=''){
			if(is_string($where)and $where!='')
				$where=" WHERE $where ";
			elseif(is_array($where)){
				if(count($where)>0)
					$where=" WHERE ".(implode(' AND ',$where))." ";
				else $where='';
			}
			$set=array();
			foreach($data as $key=>$value){
				$set[$key]="`".($key)."`='".($escapestr?mysql_real_escape_string($value):$value)."'";
			}
			$sql = "UPDATE `$table` SET ".(implode(',',$set)).$where;
			//echo"$q<br>";
				//"ON DUPLICATE KEY UPDATE `field_def`='".(mysql_real_escape_string($field_def))."' ";
			$q = new sql($sql);
			return $q->q_res;
		}else return false;
	}
	//---- insert , update if exist from array ----
	//function setarr($table='',$data=array()){}*/
//---------------------------------------	
}
class sql extends parent_sql {}