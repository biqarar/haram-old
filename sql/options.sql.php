<?php

class options {




	public $id = array("type" => "int@10", "label" => "");
	public $users_id = array("type" => "int@10", "label" => "");
	public $post_id = array("type" => "int@10", "label" => "");
	public $option_cat = array("type" => "varchar@50", "label" => "");
	public $option_key = array("type" => "varchar@50", "label" => "");
	public $option_value = array("type" => "text@", "label" => "");
	public $option_meta = array("type" => "", "label" => "");
	public $option_status = array("type" => "enum@enable,disable,expire!enable", "label" => "");


	public function id() {
		$this->validate("id");
	}
	

	public function users_id(){

	}

	public function post_id(){

	}

	public function option_cat(){

	}

	public function option_key(){

	}

	public function option_value(){

	}

	public function option_meta(){

	}

	public function option_status(){

	}

}
?>