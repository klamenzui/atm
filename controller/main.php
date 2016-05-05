<?php defined('droot') OR die('No direct script access.');
require_once (dcontroller."common".ext);
class main extends common {
	public function index(){
		//echo('!!!!!mm!!!!!!!!!');
		//$q = sql::get("SELECT SUM(  `count` ) AS  `count` FROM  `a_money` GROUP BY  `cur`");
		$q = sql::q("SELECT SUM(  `count` ) AS  `count` FROM  `a_money` GROUP BY  `cur`")->row();
		// esli net deneg - soobshit'
		if($q['count']<1)
			print_r('В банкомате нет денег');
		//$this->template->set_global('set',$set);
		$this->template->set_global('content',out::view("pages/main")->render());
		//echo "h";
		/*
		$out=out::view("q");
		$out->v=$v; need template class?
		echo $out->render();*/
		//echo out::view("main")->render();
		//$q=$db->q("select * from reports where id=?i[]");//\?[isf]\[(.*)\]
		//$q=sql::add("reports",array('content'=>'ffff'));
		//$q=sql::get("select * from reports ");//->rows();//->q_res;//where id=?i[]");
		/*while ($a = $q->row()){
				$data['data'][] = array(
					'id'=>$a['id'],
					'content'=>$a['content']
				);
			}*/
		//echo"<pre>";
		//out::view("main")->render();
		//var_dump(sql::get("select * from reports where id=@",array('@:i'=>"1")));
		//print_r($arr);
		//echo"</pre>";
		/*$qid = $db->q("SELECT `qns`.`department_id`,`qns`.`name`,`qn_qs`.* 
			FROM `b_questionnaires` as `qns`,`b_qnaire_questions` as `qn_qs`
			WHERE `qns`.`department_id`=$dep_id AND `qn_qs`.`qnaire_id`=`qns`.`id`");
			while ($a = $db->get_assoc($qid)){
				$data['data'][] = array(
					'id'=>$a['id'],
					'department_id'=>$a['department_id'],
					'name'=>$a['name'],
					'qnaire_id'=>$a['qnaire_id'],
					'question'=>$a['question'],
					'combo'=>$a['answers_combo']!='',
					'answers_combo'=>$a['answers_combo'],
				
				);
			}*/
	}
}