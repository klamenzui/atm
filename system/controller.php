<?php defined('droot') OR die('No direct script access.');
class controller {
	public $config=array();
	public $auth = false;
	public $user = array();
	public function __construct($config=array()/*Request $request*/)
	{
		if(count($config)>0)
			$this->config = $config;
		$sess=sess::login();
		$this->auth=$sess->auth;
		$this->user=$sess->user;
		$this->before();
	}
	public function __destruct() {
		$this->after();
	}
	public function before()
	{
	}
	public function after()
	{
	}
} // End Controller