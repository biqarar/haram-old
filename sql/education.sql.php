<?php
/**
 * @author reza mohiti
 */
namespace sql;
class education {
	public $id      = array('type'=> 'int@10', 'autoI', 'label' => 'education_id');
	public $group   = array('type'=> 'enum@academic,howzah!academic', 'label' => 'education_group');
	public $section = array('type'=> 'varchar@32', 'label' => 'education_section');

	public $unique = array("section");

	public function id() {
		$this->validate("id");
	}

	public function group() {
		$this->form("select")->name("group");
		$this->setChild($this->form);
	}
	public function section() {
		$this->form("#fatext")->name("section")->required();
		$this->validate()->farsi()->form->farsi("education is not valid");
	}
}
?>