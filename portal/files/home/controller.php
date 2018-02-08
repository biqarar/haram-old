<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller{
	public $permission = array("files" => array("insert" => array("public"),
		"update" => array("public")));
	function config(){
		$this->listen(array(
			"max" =>3,
			"url"=>array('type'=>'files', 'status'=>"add", "base"=> "/^(plan|post|users)$/")
			), function(){
			save(array("files","option"));
			$this->permission = array("files" => array("insert"));
		});
		$this->listen(array(
			"max" => 1,
			"url" => array("edit", "/^\d+$/")
			), array("files", "option"));

		/**
		 * tags
		 */
		$this->listen(array(
			"max" =>2,
			"url"=>array('status' => 'add', 'type'=>'tag')
			), function(){
			save(array("files","tagOption"));
			$this->permission = array("file_tag" => array("insert"));
		});
		$this->listen(array(
			"max" =>6,
			"url" =>array('type' => 'tag', "status"=> "api", "session" => "/^\d+$/")
			), function(){
			save(array("files","tagOption"));
			$this->permission = array("file_tag" => array("insert"));
		});
	}
}
?>