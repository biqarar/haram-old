<?php
/**
* @author reza mohiti
*/
namespace sql;
class branch {

	public $id     = array('type'=> 'int@10', 'autoI', 'label' => 'branch_id');
	public $name   = array('type'=> 'varchar@32', 'label' => 'branch_name');
	public $code   = array('type'=> 'int@3', 'label' => 'branch_code');
	public $gender = array('type'=> 'enum@male,female,malefemale!male');
	
	public $unique = array("name");

	public function id() {
		$this->validate("id");
	}

	public function name() {
		$this->form("#fatext")->name("name")->required();
		$this->validate()->farsi()->form->farsi("branch name must be persian");
	}

	public function code() {
		// $this->validate()->number(1, 3);
	}

	public function gender() {
		$this->form("select")->name("gender")->label("gender");
		$this->setChild($this->form);
	}
}

?>