<?php
namespace sql;
class certification {
	public $id                = array('type'=> 'int@10', 'autoI', 'label' => 'certification_id');
	public $classification_id = array('type'=> 'int@10', 'label' => 'classification_id');
	// public $date_design       = array('type'=> 'int@10', 'label' => 'certification_date_design');
	public $date_print        = array('type'=> 'int@10', 'label' => 'certification_date_print');
	public $date_deliver      = array('type'=> 'int@10', 'label' => 'certification_date_deliver');
	public $date_request      = array('type'=> 'int@10', 'label' => 'certification_date_request');

	// public $unique = array("name");
	// public $index = array("users_id", "classification_id");

	// public $foreign = array("classification_id" => "classification@id!id");

	public function id() {
		$this->validate("id");
	}

	public function classification_id() {
		$this->form("select")->name("classification_id");
		$this->setChild();
	}

	// public function date_design() {
	// 	$this->form("#date")->name("date_design");
	// }

	public function date_print() {
		$this->form("#date")->name("date_print");
	}

	public function date_deliver() {
		$this->form("#date")->name("date_deliver");
	}

	public function date_request() {
		$this->form("#date")->name("date_request");
	}
}
?>