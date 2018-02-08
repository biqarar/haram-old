<?php
/**
* @author reza mohiti
*/
namespace sql;
class hefz_group {

	public $id = array("type" => "int@10",'autoI', "label" => "hefz_ligs_id");
	public $lig_id = array("type" =>"int@10", "label" => "hefz_teams_lig_id");
	public $name = array("type" => "varchar@255", "label" => "hefz_group_name");
	public $description = array("type" => "text@", "label" => "description");

	public $foreign = array("lig_id" => "hefz_ligs@id!name");

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
		$this->validate()->farsi()->form->farsi("name is not valid");

	}


	public function description() {
		$this->form("text")->name("description");
	}

}

?>