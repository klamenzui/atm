<?php defined('droot') OR die('No direct script access.');

abstract class parent_loader{
	public function __construct(){
	
	}
	public static function get($file_path,$allowed_dirs, $type = 0)//0-content||1-require
	{
		//file_path='view/js/main.js'
		if(!empty($file_path)){
			if(is_array($allowed_dirs)and count($allowed_dirs)>0){
				if(is_string($file_path)){
					$fp=str_replace('\\','/',$file_path);
					$fp=$fp[0]=='/'?substr($fp,1):$fp;
					$fp=str_replace('/',ds,$fp);
					$dirs=explode('/',$file_path);
				}elseif(is_array($file_path))
					$dirs=$file_path;
				else return false;//die('wrong path :'.var_dump($file_path));
				if(($dirs_count=count($dirs))>0){
					$dirs_count=$dirs_count-1;
					$a_dirs=$allowed_dirs;
					for($i=0;$i<$dirs_count;$i++){
						if(isset($a_dirs[$dirs[$i]]))
							$a_dirs=$a_dirs[$dirs[$i]];
						else
							return false;//die("dir not exist :{$dirs[$i]}");
					}
					$fp=implode(ds,$file_path);
				}else return false;//die("empty path");
			}else return false;//die('set alowed dirs');
			
			if(is_file(droot.$fp)){
				if($type==0){
					//if(ob_get_level())
					//	ob_end_clean(); 
					//readfile(droot.$fp);
					//echo file_get_contents(droot.$fp);
				}
				//else
					include_once(droot.$fp);
				return true;
			}else return false;//die("file <b>".droot.$fp."</b> not found");
		}else return false;//die("empty path");
	}
}
class loader extends parent_loader {}