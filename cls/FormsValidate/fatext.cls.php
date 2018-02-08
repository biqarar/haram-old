<?php
class FormsValidate_fatext_cls extends FormsValidate_lib{
	public $farsi = [3, 32];
	
	public $form = array(
		"set" => "only persian text accepted",
		"farsi" => "The text should be between 3 and 32 characters long"
		);
}
?>