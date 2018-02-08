<?php
/**
* @author reza mohiti
*/
namespace sql;
class hefz_race {

	public $id = array("type" => "int@10", "label" => "hefz_race_id");
	public $lig_id = array("type" =>"int@10", "label" => "hefz_teams_lig_id");
	public $type = array("type" => "enum@حذفی,دوره ای,دوستانه!حذفی", "label" => "hefz_race_type");
	public $hefz_team_id_1 = array("type" => "int@10", "label" => "hefz_race_hefz_team_id_1");
	public $hefz_team_id_2 = array("type" => "int@10", "label" => "hefz_race_hefz_team_id_2");

	public $status     = array('type'=> 'enum@ready,running,done!ready', 'label' => 'hefz_race_status');
	public $date   = array('type'=> 'int@8', 'label' => 'hefz_race_date');
	public $time   = array('type'=> 'int@4', 'label' => 'hefz_race_time');
	public $place       = array('type'=> 'varchar@64', 'label' => 'hefz_race_place');

	public $hefz_group = array("type" =>"int@10", "label" => "hefz_group");
	public $manfi1 = array("type" => "float@", "lable"=> "hefz_race_result_value");
	public $manfi2 = array("type" => "float@", "lable"=> "hefz_race_result_value");

	public $result1 = array("type" => "float@", "lable"=> "hefz_race_result_value");
	public $result2 = array("type" => "float@", "lable"=> "hefz_race_result_value");

	public $rate1 = array("type" => "int@", "lable"=> "hefz_race_rate_value");
	public $rate2 = array("type" => "int@", "lable"=> "hefz_race_rate_value");


	public $name = array("type" => "varchar@255", "label" => "hefz_race_name");

	public $foreign = array(
		"hefz_team_id_1"=> "hefz_teams@id!name",
		"hefz_team_id_2" =>
		"hefz_teams@id!name",
		"lig_id"=> "hefz_ligs@id!name");

	public function id() {
		$this->validate("id");
	}

	public function lig_id(){
		$this->form("select")->name("lig_id")->addClass("notselect")->required();
		$this->setChild(function($q){

				$list = isset($_SESSION['my_user']['branch']['selected']) ?
							  $_SESSION['my_user']['branch']['selected'] : array();

				$q->groupOpen();
				foreach ($list as $key => $value) {
					if($key == 0){
						$q->condition("where", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}
				$q->groupClose();

		});
	}

	public function hefz_group(){
		$this->form("select")->name("hefz_group")->addClass("notselect")->style("display:none;");

	}

	public function hefz_team_id_1() {
		$this->form("select")->name("hefz_team_id_1")->addClass("notselect")->required();
		$this->setChild(function($q){
			// var_dump($q);exit();
			// if(!isset($_SESSION['supervisor'])){

				$list = isset($_SESSION['my_user']['branch']['selected']) ?
							  $_SESSION['my_user']['branch']['selected'] : array();


				$q->joinHefz_ligs()->whereId("#hefz_teams.lig_id");
				$q->groupOpen();
				foreach ($list as $key => $value) {
					if($key == 0){
						$q->condition("where", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}
				$q->groupClose();

		});
	}

	public function hefz_team_id_2() {
		$this->form("select")->name("hefz_team_id_2")->addClass("notselect")->required();
		$this->setChild(function($q){

			// if(!isset($_SESSION['supervisor'])){

				$list = isset($_SESSION['my_user']['branch']['selected']) ?
							  $_SESSION['my_user']['branch']['selected'] : array();


				$q->joinHefz_ligs()->whereId("#hefz_teams.lig_id");

				$q->groupOpen();
				foreach ($list as $key => $value) {
					if($key == 0){
						$q->condition("where", "hefz_ligs.branch_id","=",$value);
					}else{
						$q->condition("or","hefz_ligs.branch_id","=",$value);
					}
				}
				$q->groupClose();

		});
	}

	public function place() {
		$this->form("#fatext")->name("place");
		$this->validate()->farsi()->form->farsi("teams place must be persian");
	}

	public function type() {
		$this->form("select")->name("type")->addClass("notselect")->required();
		$this->setChild();
	}

	public function status() {
		$this->form("select")->name("status");
		$this->setChild($this->form);
		// $this->validate();
	}

	public function time() {
		$this->form("text")->name("time")->time('time');
		$this->validate()->time()->form->time("start time is not valid");
	}

	public function date() {
		$this->form("#date")->name("date");
		$this->validate()->date()->form->date("start date is not valid");
	}


	public function name() {
		$this->form("#fatext")->name("name")->label("توضیحات");
		$this->validate()->farsi()->form->farsi("teams name must be persian");
	}

	public function manfi1(){}
	public function manfi2(){}


}

?>