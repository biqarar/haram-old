<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_listapi() {

	$dtable = $this->dtable->table("hefz_race")
		->fields(
			'id', 
			'ligname', 
			'team1',
			'result1',
			'rate1 rate1',
			'hefz_team_id_2 team2',
			'result2',
			'rate2 rate2',
			'type',
			'status',
			'date',
			'time',
			'place',
			'name',
			'id delete',
			'id race')
		->search_fields(
			'ligname hefz_ligs.name',
			'team1 hefz_teams.name',
			'hefz_team_id_2',
			'type',
			'name hefz_race.name')
		->query(function($q){
			
			$q->joinHefz_teams()->whereId("#hefz_race.hefz_team_id_1")->fieldName("team1");
			$q->joinHefz_ligs()->whereId("#hefz_teams.lig_id")->fieldId("lig_id")->fieldName("ligname");
			$q->groupOpen();
			foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("and", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}
			$q->groupClose();
			// echo ($q->select()->string());exit();
		})
		->search_result(function($result){
			$vsearch = $_POST['search']['value'];
			$vsearch = str_replace(" ", "_", $vsearch);
			$result->groupOpen();
			$result->condition("and", "hefz_ligs.name", "LIKE", "'%$vsearch%'");
			$result->condition("or", "hefz_teams.name", "LIKE", "'%$vsearch%'");
			$result->condition("or", "hefz_race.type", "LIKE", "'%$vsearch%'");
			$result->condition("or", "hefz_race.name", "LIKE", "'%$vsearch%'");
			$result->groupClose();
			
			})
		->result(function($r) {
			
			$r->rate1 = ($r->rate1 == "") ? "0" : $r->rate1;
			$r->rate2 = ($r->rate2 == "") ? "0" : $r->rate2;
			

			$r->result1 = ($r->result1 == "") ? "0" : $r->result1;
			$r->result2 = ($r->result2 == "") ? "0" : $r->result2;
			
			$r->team2 = $this->find_team_name($r->team2);
	
			$r->delete = $this->tag("a")->class("icoredclose")->href("hefzlig/race/status=delete/id=". $r->delete)->render();
			$r->race = $this->tag("a")->href("hefzlig/race/status=racing/id=". $r->race)->addClass("icoallusers")->render();


		});
		$this->sql(".dataTable", $dtable);
	}

	public function result($race_id = false) {
		$result = $this->sql(".hefzlig.race_result",$race_id);
		$all = array();
		foreach ($result as $key => $value) {
			$all[] = $value;
		}
		return (isset($all[0]) AND isset($all[1])) ? $all[0]["main_result"] . "  -  " . $all[1]['main_result'] : "نا مشخص";
	}

	public function find_team_name($team_id = false) {
		return $this->sql()->tableHefz_teams()->whereId($team_id)->limit(1)->select()->assoc("name");
	}

	public function makeQuery() {

		$x = $this->sql(".branch.hefz_teams", post::hefz_team_id_1());
		$y = $this->sql(".branch.hefz_teams", post::hefz_team_id_2());

		if($x != $y) {
			debug_lib::fatal("خطا در تطابق شناسه شعبه دو تیم");
		}

		if(post::hefz_team_id_1() == post::hefz_team_id_2()) {
			debug_lib::fatal("تیم اول و دوم نباید هم نام باشند");
		}

		if(post::type() == "دوره ای") {
			$check  = $this->sql()->tableHefz_teams()->whereId(post::hefz_team_id_1())->select()->assoc("hefz_group_id");
			$check2 = $this->sql()->tableHefz_teams()->whereId(post::hefz_team_id_2())->select()->assoc("hefz_group_id");
			if($check != $check2) {
				debug_lib::fatal("مسابقه دوره ای بین تیم های هم گروه برگزار می شود");
			}
		}
		
		return $this->sql()->tableHefz_race()
			->setHefz_team_id_1(post::hefz_team_id_1())
			->setHefz_team_id_2(post::hefz_team_id_2())
			->setName(post::name())
			->setDate(post::date())
			->setTime("#'". post::time(). "'")
			->setPlace(post::place())
			->setType(post::type());

	}


	public function post_add_hefz_race() {


		//------------------------------ insert race
		$sql = $this->makeQuery()->insert()->LAST_INSERT_ID();

		//------------ set presence
		$set_presence = $this->set_presence($sql);
		//------------------------------ commit code
		$this->commit(function($id = false) {
			debug_lib::true("ثبت مسابقه انجام شد");
		// 	debug_lib::true("<a href='hefzlig/race/status=racing/id=$id' target='_blank' style='text-decoration: none; color:white; cursor: pointer;'><div style='width: 70px;background: #0C706F;border-radius: 7px;padding: 25px 50px 25px 50px !important;text-align: center;display: inline-block;'>شروع مسابقه</div></a><br>");
		}, $sql);

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert race failed]]");
		});
	}

	public function post_edit_hefz_race() {
		// //------------------------------ update race
		// $sql = $this->makeQuery()->whereId($this->xuId())->update();
		
		// //------------------------------ commit code
		// $this->commit(function() {
		// 	debug_lib::true("[[update race successful]]");
		// });

		// //------------------------------ update code
		// $this->rollback(function() {
		// 	debug_lib::fatal("[[update race failed]]");
		// });
	}



	public function set_presence($race_id = false) {
		$race = $this->sql()->tableHefz_race()->whereId($race_id)->limit(1)->select()->assoc();
		$update = $this->sql()->tableHefz_race()
		->setPresence1($this->set_presence_($race['hefz_team_id_1']))
		->setPresence2($this->set_presence_($race['hefz_team_id_2']))
		->whereId($race_id)->update();
	}

	public function set_presence_($team_id = false ){
		$list = $this->sql()->tableHefz_teamuser()->whereHefz_team_id($team_id)->select()->allAssoc();
		$return = "";
		foreach ($list as $key => $value) {
			$return .= $value['id'] . ",";
		}
		return $return;
	}


}
?>