<?php
namespace sql;
class classification {
	public $id              = array('type'=> 'int@10', 'autoI', 'label' => 'classification_id');
	public $users_id        = array('type'=> 'int@10', 'label' => 'users_id');
	public $classes_id      = array('type'=> 'int@10', 'label' => 'classes_id');
	public $date_entry      = array('type'=> 'int@10', 'lable' => 'classification_date_entry');
	public $date_delete     = array('type'=> 'int@10', 'label' => 'classification_date_delete');
	public $because         = array('type'=> 'enum@cansel,move!cancel', 'label' => 'classification_because');
	public $mark            = array('type'=> 'float@12', 'label' => 'classification_mark');
	public $plan_section_id = array('type'=> 'varchar@64', 'label' => 'plan_section_id');

	public $unique          = array("name");
	public $index           = array("users_id", "classes_id");

	public $foreign         = array("classes_id" => "classes@id!name",
	"users_id"              => "users@id!id",
	"plan_section_id"       => "plan_section@id!section");

	public function id() {
		$this->validate("id");
	}

	public function users_id() {
		$this->form("#number")->name("users_id")->disabled("disabled");
		// $this->setChild();
		$this->validate("id");
	}

	public function classes_id() {
		$this->form("#number")->name("classes_id")->disabled("disabled");
		// $this->setChild();
	}

	public function date_entry() {
		$this->form("#date")->name("date_entry")->disabled("disabled");
	}

	public function date_delete() {
		$this->form("#date")->name("date_delete")->required();
	}

	public function because() {
		$this->form("select")->name("because")->required();
		$this->setChild($this->form);
	}

	public function mark() {
		$this->form("text")->name("mark");
		$this->validate()->float()->form->float("classification mark is not valid");
	}

	public function plan_section_id() {
		$this->form("select")->name("plan_section_id");
		// $this->setChild();
	}
}
?>