<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class controller extends main_controller{
	public $access = true;
	function config(){
		$this->listen(array(
			"max" => 2,
			"url" => array("api")
			), array("class" => "test", "method"=> "api", "mod" => "list"));
		
		$this->listen(array(
			"max" => 2,
			"url" => array("status" => "add", "id" => "/^\d+$/")
			), array("class" => "test", "method"=> "option"));
			
	}
}
?>