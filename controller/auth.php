<?php defined('droot') OR die('No direct script access.');
require_once (dcontroller."common".ext);
class auth extends common {
	public function index(){
		//die('^'.$this->config['site']['url']['base'].'^');
		//require_once (dcontroller."captcha".ext);
		if((isset($_POST['login'],$_POST['password'])and //captcha::valid($_POST['txtCaptcha']) and
			sess::login($_POST['login'],$_POST['password'])->auth)or sess::login()->auth){
			//echo '^'.$this->config['site']['url']['base'].'^';
			header('Location: /'.$this->config['site']['url']['base']);
		}else{
			$this->template->set_global(array(
				'sidebar'=>out::view("pages/auth")->render(),
				'content'=>out::view("pages/auth")->render()
			));
		}
	}
	public function logout(){
		sess::logout();
		//die('^'.$this->config['site']['url']['base'].'^');
		header('Location: /'.$this->config['site']['url']['base']);
	}
}