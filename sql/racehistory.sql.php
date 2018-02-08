<?php
namespace sql;
class racehistory {
	public $id       = array('type'=> 'int@10', 'autoI', 'label' => 'racehistory_id');
	public $users_id = array('type'=> 'int@10', 'label' => 'users_id');
	public $field    = array('type'=> 'varchar@64', 'label' => 'racehistory_field');
	public $club     = array('type'=> 'varchar@64', 'label' => 'racehistory_club');
	public $step     = array('type'=> 'enum@provincial,country,international!provincial', 'label' => 'racehistory_step');
	public $rank     = array('type'=> 'int@2', 'label' => 'racehistory_rank');
	public $year     = array('type'=> 'int@4', 'label' => 'racehistory_year');

	public $unique = array("id");
	public $index = array("users_id");

	// public $foreign = array("users_id" => "users@id!id");

	public function id() {
		$this->validate("id");
	}
	
	public function users_id() {
		// $this->form("select")->name("users_id");
		// $this->setChild();
	}
	
	public function field() {
		$this->form("#fatext")->name("field");
		// $this->validate()->farsi(3,64);
	}
	
	public function club() {
		$this->form("#fatext")->name("club");
		// $this->validate()->farsi(3,64);
	}
	
	public function step() {
		$this->form("select")->name("step");
		$this->setChild($this->form);
	}
	
	public function rank() {
		$this->form("#number")->name("rank");
		// $this->validate()->number(1, 2);
	}
	
	public function year() {
		$this->form("#number")->name("year");
		// $this->validate()->number(4);
	}
}
?>