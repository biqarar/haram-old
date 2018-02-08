<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public $team1_data, $team2_data, $race_id,$presence1,$presence2;

	public function config() {
		
		//------------------------------ globals
	
		$this->race_id = $this->sql("#online");
// var_dump($this->race_id);exit();
		$this->data->raceid = $this->race_id;

		$this->global->page_title = 'نمایش بر خط نتایج';

		$this->data->ligname = $this->sql("#ligname", $this->race_id);
		$this->data->telavat = $this->sql("#telavat", $this->race_id);

		list($this->data->team1name, $this->data->team2name) = $this->sql("#find_team_id", $this->race_id);

		$result = $this->sql("#result", $this->race_id);
		$this->data->team1data = json_encode(
				array(
					array("name" => "امتیاز", "y" => floatval($result[$this->data->team1name['id']]['main_result']), "color" => "green"),
					array("name" => "منفی", "y" => intval($result[$this->data->team1name['id']]['manfi']) *2, "color" => "red"),
					array("name" => "", 
						"y" => floatval(100 - (floatval($result[$this->data->team1name['id']]['main_result'])+
							   floatval($result[$this->data->team1name['id']]['manfi'])*2))
						, "color" => "whitesmoke")
				)
			);

		$this->data->team2data = json_encode(
				array(
					array("name" => "امتیاز", "y" => floatval($result[$this->data->team2name['id']]['main_result']), "color" => "green"),
					array("name" => "منفی", "y" => intval($result[$this->data->team2name['id']]['manfi'])*2, "color" => "red"),
					array("name" => "", 
						"y" => floatval(100 - (floatval($result[$this->data->team2name['id']]['main_result'])+
							   floatval($result[$this->data->team2name['id']]['manfi'])*2))
						, "color" => "whitesmoke")
				)
			);
		$this->data->team1rate = $result[$this->data->team1name['id']]['rate'];
		$this->data->team2rate = $result[$this->data->team2name['id']]['rate'];

		$this->data->team1result = $result[$this->data->team1name['id']]['result'];
		$this->data->team2result = $result[$this->data->team2name['id']]['result'];
		// var_dump($this->data->team1data);
		
	}
}
?>