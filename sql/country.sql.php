<?php
/**
 * @author reza mohiti
 */
namespace sql;
class country {
	public $id      = array('type'=> 'int@10', 'autoI', 'label' => 'country_id');
	public $name    = array('type'=> 'varchar@32', 'label' => 'country_name');
	
	public $unique = array("name");

	public function id() {
		$this->validate("id");
	}

	public function name() {
		$this->form("#fatext")->name("name")->required();
		$this->validate()->farsi()->form->farsi("country name should be persian");
	}
}
?>