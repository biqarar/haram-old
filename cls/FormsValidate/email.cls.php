<?php
class FormsValidate_email_cls extends FormsValidate_lib{
	public $reg = "/^[a-z0-9\_@\.]{3,}$/Ui";
	public $form = 	array(
		"set" => 'please input email',
		"reg" => 'Incorrect, Check Email'
		);
	public $sql = 	array("unique" => 'duplicate email');
}
?>