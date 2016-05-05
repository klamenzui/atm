<?php defined('droot') OR die('No direct script access.');
class common extends template {
	public $template = 'main';
	public $auth = false;
	public $user = array();
	public function before()
	{
		parent::before();
		$out=out::view("main");
		$sess=sess::login();
		$this->auth=$sess->auth;
		$this->user=$sess->user;
		$client_path=$this->config['site']['url']['base'].$this->config['site']['url']['client_path'].'/';		
		$css_theme=$this->config['site']['css_theme'];
		$glob=array(
			'user'=>$this->user,
			'title' => $this->config['site']['title'],
			'keywords' => $this->config['site']['keywords'],
			'description' => $this->config['site']['description'],
			
			'jscripts'=>array('globals','jq','main'),//,'header','content','sidebar','footer','icons','player');
			'styles'=>array("$css_theme/jq_ui",'main'),
			'url'=>array(
				'base'=>$this->config['site']['url']['base'],
				'client_path'=>$client_path,
				'css'=>$client_path.'css/',
				'js'=>$client_path.'js/',
				'media'=>$this->config['site']['url']['base'].$this->config['site']['url']['base'].'/'
			),
			'sidebar'=>($this->auth?out::view("pages/sidebar"):''),
			'account'=>($this->auth?out::view("pages/account"):'')
		);
		$out->set_global($glob);
		$this->template=$out;
		//if(!$auth)die('eee');
		//echo $out->render();
	}
	public function after()
	{		
		if(!$this->auth)$this->template->set_global(array('content'=>out::view("pages/auth"),'sidebar'=>''));
		parent::after();
		
	}
}