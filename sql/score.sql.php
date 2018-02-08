<?php 
/**
* @author reza mohiti
*/
namespace sql;
class score {

	public $id = array("type" => "int@10", "lable" => "id");
	public $classification_id = array("type" => "int@10", "lable" => "classification_id");
	public $score_type_id = array("type" => "int@10", "lable" => "score_type_id");
	public $date = array("type" => "int@10", "lable" => "score_type_id");
	public $value = array("type" => "int@10", "lable" => "value");

	public function id() {
		$this->validate("id");
	}

	public function classification_id() {
		$this->validate("id");
	}

	public function score_type_id() {
		$this->validate("id");
	}

	public function value() {
		$this->validate()->float()->form->float("number is not valid");
	}
	public function date() {
		// $this->validate()->float()->form->float("number is not valid");
	}
}
?>