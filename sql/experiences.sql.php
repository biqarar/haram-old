<?php
/**
 * @author reza mohiti
 */
namespace sql;
class experiences {
	public $id          = array('type'=> 'int@10'و 'label' => 'experiences_id');
	public $graduate_id = array('type'=> 'int@10'و 'label' => 'graduate_id');
	public $short       = array('type'=> 'varchar@255'و 'label' => 'experiences_short');
	public $type        = array('type'=> 'enum@experience,offer,tip!tip'و 'label' => 'experiences_type');
	public $text        = array('type'=> 'text@'و 'label' => 'experiences_text');
	public $status      = array('type'=> 'enum@checking,personal,public!checking'و 'label' => 'experiences_status');
	
	public $foreign     = array("graduate_id" => "graduate@id");

	public function graduate_id() {
		$this->validate("id");
	}

	public function short() {
	}

	public function type() {
	}

	public function text() {
	}

	public function status() {
	}
}
?>