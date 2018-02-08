<?php
/**
* @author reza mohiti
*/
namespace sql;
class hefz_teams {
	public $id = array("type" =>"int@10", "label" => "hefz_teams_id");
	public $lig_id = array("type" =>"int@10", "label" => "hefz_teams_lig_id");
	public $hefz_group_id = array("type" => "int@10", "label" => "hefz_group_name");
	public $name = array("type" =>"varchar@255", "label" => "hefz_teams_name");
	public $teacher = array("type" =>"int@10", "label" => "hefz_teams_teacher");
	public $hefz = array("type" =>"text@", "label" => "hefz_teams_hefz");

	public $foreign = array("lig_id" => "hefz_ligs@id!name", "hefz_group_id" => "hefz_group@id!name");


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

	public function name() {
		$this->form("#fatext")->name("name")->required();
		$this->validate()->farsi()->form->farsi("teams name must be persian");
	}

	public function hefz_group_id() {

		$this->form("select")->name("hefz_group_id")->addClass("notselect")->required();

		$this->setChild();
	}


	public function teacher(){
		 $this->form("text")->name("teacher")->required()->id("teachername")->addClass("select-teacher")->data_url("teacher/api/");
	}

	public function hefz() {
		$this->form("text")->name("hefz");
		$this->validate()->description()->form->description("hefz must be between 3 and 255 charset");
	}
}

?>