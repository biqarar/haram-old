<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public $lig_id;
	
	public function sql_lig_name($lig_id = false) {
		return $lig_name = $this->sql()->tableHefz_ligs()->whereId($lig_id)->limit(1)->select()->assoc("name");

	}
	public function sql_mrhefz($lig_id = false) {
		
		$this->lig_id = $lig_id;

		$lig_name = $this->sql()->tableHefz_ligs()->whereId($lig_id)->limit(1)->select()->assoc("name");

		$teams = $this->sql()->tableHefz_teams()->whereLig_id($lig_id)->select()->allAssoc();
		
		$result = array();

		foreach ($teams as $key => $value) {

			$teamusers = $this->sql()
							  ->tableHefz_teamuser()
							  ->whereHefz_team_id($value['id'])
							  ->select()
							  ->allAssoc();
							  // var_dump($teamusers->string());exit();
			
			foreach ($teamusers as $teamusersid => $v) {

				$usersid = $v['users_id'];
				
				$sum = $this->sql()->tableHefz_race_result()->whereHefz_teamuser_id($v['id'])->select()->allAssoc();
				$sum_result = 0;
				$i = 0;
				foreach ($sum as $index => $s) {
					$i++;
					$sum_result = $sum_result + intval($s['value']);
				}
				if($i == 0) $i=2;
				// round(floatval($d), 2);
				// $result[$usersid]['average'] = round(($sum_result) / ($i/2),2);
				$result[$usersid]['sum'] = $sum_result;
				$result[$usersid]['team'] = $value['name'];
				
			}

		}

		$return = array();
		$ar1 = array();
		// $ar2 = array();
		$i = 0;
		foreach ($result as $key => $value) {
			$return[$i]['id'] = 0;
			$return[$i]['name'] = $this->sql(".userNameFamily.name", $key);
			$return[$i]['family'] = $this->sql(".userNameFamily.family", $key);
			$return[$i]['team'] = $value['team'];
			$return[$i]['sum'] = $value['sum'];
			// $return[$i]['average'] = $value['average'];

			$ar1[] = $value['sum'];
			$i++;
		}
		array_multisort($ar1,SORT_DESC,$return);
		

		//rate for mr hefz
		$index = $return[0]['sum'];
		$j = 1;
		$count = 0;
		foreach ($return as $key => $value) {
			if($value['sum'] == $index) {
				$return[$key]['id'] = $j;
			}else{
				$index = $value['sum'];
				$j = $j+ $count;
				$count=0;
				$return[$key]['id'] = $j;
			}
			$count++;
		}

		return $return;
		
		var_dump($ar1,$return);
		exit();		
	}

}
?>