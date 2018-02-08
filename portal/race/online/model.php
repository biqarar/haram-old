<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function sql_telavat ($race_id = false) {
		$check = $this->sql()->tableLogs()->whereLog_meta("telavat_hefz_race=$race_id")->limit(1)->select();
		
		if($check->num() == 1) {
			$return =  $check->assoc("log_data");
		}else{
			$return =  "telavat1";
		}
		return ($return == "telavat1") ? "تلاوت اول" : "تلاوت دوم";
	}

	public function sql_online(){
		$online =  $this->sql()->tableHefz_race()->whereStatus("running")->select();

		if($online->num() >= 1) {
			return $online->assoc("id");
		}else{
			$online =  $this->sql()->tableHefz_race()->whereStatus("done")->select();

			if($online->num() >= 1) {
				return $online->assoc("id");
			}else{
				return  $this->sql()->tableHefz_race()->limit(1)->select()->assoc("id");
			}
		}
	}

	public function sql_ligname($raceid = false) {
		$ret = $this->sql()->tableHefz_race()->whereId($raceid);
		$ret->joinHefz_teams()->whereId("#hefz_race.hefz_team_id_1");
		$ret->joinHefz_ligs()->whereId("#hefz_teams.lig_id")->fieldName("ligname");
		$ret = $ret->select()->assoc("ligname");
		return $ret;
	}

	public function sql_result($raceid = false){
		return $this->sql(".hefzlig.race_result", $raceid);
	}


	public function sql_find_team_id ($race_id = false ){
		$return = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select()->assoc();
		$team1 = $this->sql()->tableHefz_teams()->whereId($return['hefz_team_id_1'])->limit(1)->select()->assoc("name");
		$team2 = $this->sql()->tableHefz_teams()->whereId($return['hefz_team_id_2'])->limit(1)->select()->assoc("name");
		return array(
			array("id" => $return['hefz_team_id_1'], "name" => $team1),
			array("id" => $return['hefz_team_id_2'], "name" => $team2)
			);

	}

}
?>