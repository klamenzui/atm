<?php defined('droot') OR die('No direct script access.');
require_once (dcontroller."common".ext);
require_once (dmodel."transaction".ext);
class trans extends common {
	public function index(){
		$this->template->set_global('content',out::view("pages/trans/banknotes",array('data'=>transaction::banknotes()))->render());
	}
	public function balance(){
		$this->template->set_global('content',out::view("pages/trans/balance",array('data'=>transaction::balance()))->render());
	}
	public function withdraw(){
		$a = (isset($_REQUEST['s'])?'withdraw':'banknotes');
		if(isset($_REQUEST['s'])){
			$data = transaction::withdraw($_REQUEST['s']);
		}else{
			$data = transaction::banknotes();
		}
		$this->template->set_global('content',out::view("pages/trans/$a",array('data'=>$data))->render());
	}
	public function sendfunds(){
		
		$data = (isset($_REQUEST['s'], $_REQUEST['num'])?transaction::sendfunds($_REQUEST['s'], $_REQUEST['num']):'');
		$this->template->set_global('content',out::view("pages/trans/sendfunds",array('data'=>$data))->render());
	}
	public function addfunds(){
		$data = (isset($_REQUEST['s'])?transaction::addfunds($_REQUEST['s']):'');
		$this->template->set_global('content',out::view("pages/trans/addfunds",array('data'=>$data))->render());
	}
}