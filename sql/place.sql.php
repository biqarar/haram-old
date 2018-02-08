<?php
namespace sql;
class place {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'place_id');
	public $name        = array('type'=> 'varchar@32', 'label' => 'place_name');
	public $multiclass  = array("type" => 'enum@no,yes!no', "label" => "place_multiclass");
	public $status  = array("type" => 'enum@enable,disable!enable', "label" => "place_status");
	public $branch_id   = array('type'=> 'int@10', 'label' => 'branch_id');
	public $description = array('type'=> 'varchar@255', 'label' => 'place_description');

	public $unique = array("name");
	public $index = array("branch_id");

	public $foreign = array("branch_id" => "branch@id!name");

	public function id() {
		$this->validate("id");
	}
	
	public function name() {
		$this->form("#fatext")->name("name")->required();
		$this->validate()->farsi()->form->farsi("place name is not valid");
	}

	public function multiclass() {
		$this->form("radio")->name("multiclass")->label("multiclass");
		$this->setChild($this->form);
	}

	public function status() {
		$this->form("radio")->name("status")->label("status");
		$this->setChild($this->form);
	}
	
	public function branch_id() {
		$this->form("select")->name("branch_id");
		$this->setChild();
	}
	
	public function description() {
		// $this->form("textarea")->name("description");
		// $this->validate()->description()->form->description("description must be between 3 and 255 charset");
	}
}
?>