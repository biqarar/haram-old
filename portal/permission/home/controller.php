<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{
	function config(){
		$this->listen(array(
			"max" =>3,
			"url" => array("apidelete", "id" => "/^\d+$/")
		)
		,function(){
			save(array("permission", "option", "mod" => "deleteapi"));
			$this->permission = array("permission" => array("delete" => array("public")));
		});

		$this->listen(array(
			"max" =>3,
			"url" => array("apishowbranch", "username" => "/^\d+$/")
		)
		,function(){
			save(array("permission", "option", "mod" => "apishowbranch"));
			$this->permission = array("permission" => array("delete" => array("public")));
		});
	}
}
?>