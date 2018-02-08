<?php
/**
 * @author Reza Mohiti
 */

namespace sql;

class bridge {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'bridge_id');
	public $users_id    = array('type'=> 'int@10', 'label' => 'users_id');
	public $title       = array('type'=> 'enum@phone,mobile,email,home_address,study_address,work_address,zipcode,website,fax!phone', 'label' => 'bridge_title');
	public $value       = array('type'=> 'varchar@255', 'label' => 'bridge_value');
	public $description = array('type'=> 'varchar@255', 'label' => 'bridge_description');
	
	public $indexs      = array("users_id");
	public $uniques     = array("users_id", "title", "value");
	public $foreign     = array("users_id"=> "users@id");

	public function id() {
		$this->validate("id");
	}
	public function users_id() {
		$this->form("hidden")->name("users_id")->disabled("disabled");
		// $this->setChild();
		$this->validate("id");
	}

	public function title() {
		$this->form("select")->name("title");
		$this->setChild($this->form);
	}

	public function value() {
		$this->form("text")->name("value")->required();
		$this->validate()->reg("/^(.*)$/")->form->reg("entered value is not valid");
	}

	public function description() {
		$this->form("text")->name("description");
		$this->validate()->description();
	}
}
?>