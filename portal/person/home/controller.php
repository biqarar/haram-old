<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */
class controller extends main_controller{
		
	function config(){
		
		//------------------------------ person api to get list of auto complete 
		$this->listen(array(
			"max" => 2,
			"url" => array("api", "/^(.*)$/")
			),
			function() {
				save(array("class" => "person", "method"=> "api", "mod" => "list"));
				$this->permission  =array("classification" => array("select" => array("public", "private")));
			} 
		);

		//------------------------------ person api to get list of auto complete 
		$this->listen(array(
			"max" => 3,
			"url" => array("extera" , "status" => "detail")
			),
			function() {
				save(array("person", "exteradetail"));	
				$this->permission  =array("person_extera" => array("select" => array("public", "private")));
			} 
		);

		//------------------------------ person api to get list of auto complete 
		$this->listen(array(
			"max" => 3,
			"url" => array("extera")
			),
			function() {
				save(array("person", "extera"));	
				$this->permission  =array("person_extera" => array("select" => array("public", "private")));
			} 
		);
	}		
}
?>