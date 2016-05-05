<?php defined('droot') OR die('No direct script access.');
session_start();
//require_once (dmodel."bank".ds."user".ext);
class parent_sess {
	public $auth=false;
	public $user = array();
	public $error=0;
	public static function login($login = NULL, $pass = NULL)
	{
		return new sess($login, $pass);
	}
	public static function logout()
	{
		if (isset($_SESSION['user_id']))
			unset($_SESSION['user_id']);
		setcookie('login', '', 0, "/");
		setcookie('password', '', 0, "/");
	}
	public function __construct($login = NULL, $pass = NULL)
	{
		if (isset($pass) and isset($login)){
			$sql = sql::get("SELECT * FROM `b_users`
				WHERE `num`='@login@' AND `pin`='@password@'
				LIMIT 1",array('login'=>$login,'password'=>$pass));
			if (isset($sql[0]['id'])){
				sql::up("b_users",array('logins'=>$sql[0]['logins']+1,'last_login'=>date("Y-m-d H:i:s")),"`id`={$sql[0]['id']}");
				$_SESSION['user_id'] = $sql[0]['id'];
				$this->auth=true;
				$this->user=$sql[0];
				//echo"$pass;$login";die;
			}else $this->error=1;
			//echo"! pass=$pass;login=$login";die;
		}else{
			if (!isset($_SESSION['user_id'])){
				if(!empty($_COOKIE['login']) and !empty($_COOKIE['password'])){
					$sql = sql::get("SELECT * FROM `b_users`
						WHERE `num`='@login@' AND `pin`='@password@'
						LIMIT 1",array('login'=>$_COOKIE['login'],'password'=>$_COOKIE['password']));
					if (isset($sql[0]['id'])){
						$_SESSION['user_id'] = $sql[0]['id'];
						$this->auth=true;
						$this->user=$sql[0];
						//echo"_COOKIE login={$_COOKIE['login']};password={$_COOKIE['password']}";die;
					}
					//echo"!_COOKIE login={$_COOKIE['login']};password={$_COOKIE['password']}";die;
				}
			}else{
				$sql = sql::get("SELECT * FROM `b_users`
					WHERE `id`='{$_SESSION['user_id']}' LIMIT 1");
				if (isset($sql[0]['id'])){
					$this->auth=true;
					$this->user=$sql[0];
					//echo"_SESSION user_id={$_SESSION['user_id']}";die;
				}
				//echo"! _SESSION user_id={$_SESSION['user_id']}";die;
			}			
		}
	}
	function __destruct() {
	}

}

class sess extends parent_sess {}