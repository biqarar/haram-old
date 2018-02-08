<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public $team1_data, $team2_data, $race_id,$presence1,$presence2;

	public function config() {
		
		//------------------------------ globals
	
		$this->race_id = $this->xuId();
		$this->data->raceid = $this->race_id;

		// set status race is running ...
		$this->sql(".hefzlig.set_running", $this->race_id);


		$this->data->telavat = $this->sql("#telavat", $this->race_id);

		list($this->presence1,$this->presence2) = $this->sql("#presence_list",$this->race_id);
		
		// var_dump($this->presence1);exit();

		//-*------------------ insert 0 for all users
		$this->sql("#insert_default_value", $this->race_id);

		list($this->team1_data, $this->team2_data) = $this->sql("#find_team_id", $this->race_id);

		$this->global->page_title = 'مسابقه بین ' . $this->team1_data['name'] . ' و ' . $this->team2_data['name'];

		$team1 = $this->sql(".list", "hefz_teamuser", function ($query) {
			$query->whereHefz_team_id($this->team1_data['id'])->orderSort("ASC");
			$query->joinPerson()->whereUsers_id("#hefz_teamuser.users_id")->fieldName("personname")->fieldFamily("personfamily");
		});

		// var_dump($team1);exit();
		
		$team1->removeCol("id,hefz_team_id,users_id,sort");

		$team1->addColFirst("absence","حضور")->select(-1, "absence")->html("{$this->team1_data['id']}|%id%");
		
		$team1->addColEnd("race1","تلاوت اول")->select(-1, "race1")->html("race1|%id%");
		$team1->addColEnd("race2","تلاوت دوم")->select(-1, "race2")->html("race2|%id%");
		$this->data->team1['id'] = $this->team1_data['id'];
		$this->data->team1['name'] = $this->team1_data['name'];
		$this->data->team1['list'] = $this->race_result($team1->compile());






		///////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////////




		$team2 = $this->sql(".list", "hefz_teamuser", function ($query) {
			$query->whereHefz_team_id($this->team2_data['id'])->orderSort("ASC");
			$query->joinPerson()->whereUsers_id("#hefz_teamuser.users_id")->fieldName("personname")->fieldFamily("personfamily");
		});
		
		$team2->removeCol("id,hefz_team_id,users_id,sort");
		$team2->addColFirst("absence","حضور")->select(-1, "absence")->html("{$this->team2_data['id']}|%id%");
		
		$team2->addColEnd("race1","تلاوت اول")->select(-1, "race1")->html("race1|%id%");
		$team2->addColEnd("race2","تلاوت دوم")->select(-1, "race2")->html("race2|%id%");
		$this->data->team2['id'] =$this->team2_data['id'];
		$this->data->team2['name'] =$this->team2_data['name'];
		$this->data->team2['list'] = $this->race_result($team2->compile());

		

		$result = $this->sql(".hefzlig.race_result", $this->race_id);

		$this->data->result1 = isset($result[$this->team1_data['id']]) ? $result[$this->team1_data['id']] : 0;
		$this->data->result2 = isset($result[$this->team2_data['id']]) ? $result[$this->team2_data['id']] : 0;
		
		

	}


	public function race_result($race =false) {
		// var_dump($race);
		foreach ($race['list'] as $key => $value) {
			$sp1 = preg_split("/\|/", $value['race1']);
			$race['list'][$key]['race1'] = $this->get_html($sp1[0], $sp1[1]);

			$sp2 = preg_split("/\|/", $value['race2']);
			$race['list'][$key]['race2'] = $this->get_html($sp2[0], $sp2[1]);

			$sp3 = preg_split("/\|/", $value['absence']);
			$race['list'][$key]['absence'] = $this->get_html_absence($sp3[0],$sp3[1]);
		}
		// var_dump($race);
		// exit();
		return $race;
	}

	public function get_html_absence($teamid = false, $teamusersid = false){
		if(preg_match("/".$teamusersid."\,/", $this->presence1) OR
		   preg_match("/".$teamusersid."\,/", $this->presence2) ){
			$c = "checked='checked' class='hefz-race-presence' ";
		}else{
			$c = "class='tr-disable hefz-race-presence'";
		}
		// var_dump($teamusersid);
		return "<label class='label-custom'>
					<input type='checkbox' $c 
					raceid='{$this->race_id}'
					teamid='{$teamid}' teamusersid='$teamusersid'>
					<span class='brk-form-custom'>
						<span>
						</span>
						<span>
						</span>
					</span>
				</label>
				";
		// exit();
	}

	public function get_html($type = false , $teamusersid = false) {
		$fa_type = ($type== "race1") ? "تلاوت اول" : "تلاوت دوم";

		return "<div class='form-element'>
					<input
					 type='text' 
					 name='$type' 
					 teamusersid='".$teamusersid."' 
					 class='race-mark' 
					 raceid='{$this->race_id}' 
					 style='width:50px !important' 
					 placeholder='$fa_type' 
					 id='$type' 
					 value='".$this->sql("#get_value",$teamusersid, $type , $this->race_id)."'>
					</div>";

	}




}
?>