<?php
namespace sql;
class city {
	public $id          = array('type'=> 'int@10', 'autoI', 'label' => 'city_id');
	public $province_id = array('type'=> 'int@10', 'label' => 'province_id');
	public $name        = array('type'=> 'varchar@32', 'label' => 'city_name');

	public $unique = array("name");
	public $foreign = array("province_id" => "province@id!name");

	public function id() {
		$this->validate("id");
	}
	
	public function province_id() {
		$this->form("select")->name("province")->required();
		$this->setChild();
	}
	
	public function name() {
		$this->form("text")->name("name")->required();
		$this->validate()->farsi()->form->farsi("city name should be persian");
	}
}
?>