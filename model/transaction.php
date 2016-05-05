<?php defined('droot') OR die('No direct script access.');

class parent_transaction{
	public $db_id = null;
	public $conf = null;
	public $q_res = null;
	public function __construct(){
		return new transaction();
	}
	public static function balance(){
		$q = sql::q("SELECT SUM(  `amount` ) AS  `amount` FROM  `b_users` WHERE id=@id@",array('id:i'=>sess::login()->user['id']))->row();
		return $q['amount'];
	}
	public static function banknotes(){
		$money = array( 50=>true,100=>true,200=>true,500=>true,1000=>true,2000=>true );
		$q = sql::q("SELECT * FROM  `a_money` WHERE cur='@cur@'",array('cur:s'=>'dollar'));
		$a_money = array();
		$total = 0;
		while ( $r = $q->row() ){
			$a_money[$r['par']]['count'] = $r['count']; 
			$a_money[$r['par']]['par'] = $r['par']; 
			$a_money[$r['par']]['total'] = $r['par']*$r['count'];
			$total += $a_money[$r['par']]['total'];
		}
		foreach( $money as $k => $v){
			foreach( $a_money as $ak => $av){
				if( ($k == $ak and $av['total']>=$k) or $k<=$total or $k<=$av['total']){
					break;
				}else{
					$money[$k] = false;
				}
			}
		}
		return $money;
	}
	public static function withdraw( $sum ){
		$res = array();
		if( $sum ){
			//$sum = $_REQUEST['s'];
			$cur = 'dollar';
			$q = sql::q("SELECT * FROM `a_money` WHERE cur='@cur@' order by `par` DESC",array('cur:s'=>'dollar'));
			$total = 0;
			while ( $r = $q->row() ){
				$a_money[$r['cur']][$r['par']]['count'] = $r['count']; 
				$a_money[$r['cur']][$r['par']]['par'] = $r['par']; 
				$a_money[$r['cur']][$r['par']]['total'] = $r['par']*$r['count'];
				$total += $a_money[$r['cur']][$r['par']]['total'];
			}
			$m = array_keys($a_money[$cur]);
			if($total >= $sum and ($sum % $m[count($m)-1])==0){
				//print_r($a_money);
				while( $sum > 0 ){
					foreach ( $m as $r ){
						if( $sum >= $r ){
							$count_par = ((int)($sum / $r));
							if( $count_par > 1)
								$count_par = (int)($count_par/2);
							echo "<br>--> sum=$sum;r=$r;--".($sum / $r)."==$count_par";
							if( $a_money[$cur][$r]['count'] >= $count_par){
								$a_money[$cur][$r]['count'] -= $count_par;
								$sum -= $count_par * $r;
								if(isset($res[$r])){
									$res[$r] += $count_par;
								}else{
									$res[$r] = $count_par;
								}
							}
							
						}
					}
				}
				//print_r($a_money);
				//echo "$sum % ".$m[0]." == 0;".($sum % $m[0]).";".$m[count($m)-1];
			}else{
				echo "!9!!";
			}
		}
		return $res;
	}
	
	public static function addFunds($sum, $num = false){
		$num = ($num == false?sess::login()->user['id']:$num);
		sql::q("UPDATE `b_users` SET  `amount` = `amount`+'@sum@' WHERE `id` = @id@",array('sum' => $sum,'id:i' => $num));
	}
	
	public static function sendFunds($sum,$num){
		sql::q("UPDATE `b_users` SET  `amount` = `amount`-'@sum@' WHERE `id` = @id@",array('sum' => $sum,'id:i' => sess::login()->user['id']));
		transaction::addFunds($sum,$num);
	}
}
class transaction extends parent_transaction {}