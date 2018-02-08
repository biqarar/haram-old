<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_apidelete(){
		$race_id = $this->xuId();

		$delete_result = $this->sql()->tableHefz_race_result()->whereHefz_race_id($race_id)->delete();

		$delete_race = $this->sql()->tableHefz_race()->whereId($race_id)->delete();

		$this->commit(function(){
			debug_lib::true("مسابقه حذف شد");
			$this->redirect("hefzlig/race");
		});

		$this->rollback(function(){
			debug_lib::fatal("اشکال در حذف مسابقه");
		});
			
	}

	public function sql_hefz_race_detail($race_id = false) {
		$query = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select();
		if($query->num() == 1) {
			$assoc = $query->assoc();
			return array($this->team_name($assoc['hefz_team_id_1']), $this->team_name($assoc['hefz_team_id_2']));
		}
		var_dump($race_id);
	}

	public function team_name($team_id = false) {
		return $this->sql()->tableHefz_teams()->whereId($team_id)->limit(1)->select()->assoc("name");
	}


}
?>