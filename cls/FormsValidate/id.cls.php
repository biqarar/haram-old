<?php 
/**
* @author reza mohiti
*/
class FormsValidate_id_cls extends FormsValidate_lib{
	public $id = [1, 10];
	public $form = array(
		"set" => "only number text accepted",
		"farsi" => "the number should be between 1 and 10"
		);
}
?>