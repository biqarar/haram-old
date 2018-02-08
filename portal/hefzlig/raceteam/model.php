<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public $lig_id;
	
	public function sql_team_name($team_id = false) {
		return $lig_name = $this->sql()->tableHefz_teams()->whereId($team_id)->limit(1)->select()->assoc("name");

	}
	public function sql_team($team_id = false) {
		$race = $this->sql()->tableHefz_race()
							 ->whereHefz_team_id_1($team_id)
							 ->orHefz_team_id_2($team_id)
							 ->select()->allAssoc();
		$return = array();

		foreach ($race as $key => $value) {
			if($value['hefz_team_id_1'] == $team_id) {
				$return[$key]['race_whit'] = $this->sql_team_name($value['hefz_team_id_2']);	
			}else{
				$return[$key]['race_whit'] = $this->sql_team_name($value['hefz_team_id_1']);				
			}
			$return[$key]['type'] = $value['type'];
			$r = $this->sql(".hefzlig.race_result", $value['id']);

			if(isset($r[$value['hefz_team_id_1']]['main_result']) AND isset($r[$value['hefz_team_id_2']]['main_result'])){
				$r1 = $r[$value['hefz_team_id_1']];
				$r2 = $r[$value['hefz_team_id_2']];
				
				$return[$key]['result'] = $r[$team_id]['main_result'] ;
				$return[$key]['sum'] = $r[$team_id]['value']  ;

				if($r1['rate'] == $r2['rate']){
					$return[$key]['rate'] = "مساوی";
				}else{
					if($r[$team_id]['rate'] == 3) {
						$return[$key]['rate'] = "برنده";
					}else{
						$return[$key]['rate'] = "بازنده";
					}
				}
			}else{
				$return[$key]['result'] = "نا مشخص";
				$return[$key]['value'] = "نا مشخص";
				$return[$key]['rate'] = "نا مشخص";
			}

		}
		return $return;
		var_dump($return);




		exit();
	}

}
?>