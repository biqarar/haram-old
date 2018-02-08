<?php
/**
* @author reza mohiti
*/
namespace sql;

class price_change {

	public $id        = array('type'=> 'int@10', 'autoI', 'label' => 'price_change_id');
	public $name      = array('type'=> 'varchar@32', 'label' => 'title');
	public $type      = array("type" => "enum@price_add,price_low" ,"label" => "price_type");
	
	public function id() {
		$this->validate("id");
	}

	public function name() {
		$this->form("text")->name("name");
		$this->validate()->farsi()->form->farsi("price_change name must be persian");
	}

	public function type(){
		$this->form("radio")->name("type")->label("type");
		$this->setChild($this->form);
	}
}

?>