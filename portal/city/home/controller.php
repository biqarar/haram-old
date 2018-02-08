<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{
	
	function config(){
		
		//------------------------------ city api (get list of city in one province)
		$this->listen(array(
			"max" => 2,
			"url" => array("api", "search" => "/^(.*)$/")
			), 
			function () {
				save(array("class" =>"city" , "method" => 'api', "mod" => "list"));
				$this->access = true;
			}
		);
	}
}
?>