<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class controller extends main_controller
{
	public $permission = array("posts" => array("insert" => array("public"),
						"update" => array("public")),
				"posts_group" => array("insert" => array("public"),
						"update" => array("public"))
	);
	function config(){
		$this->listen(array(
			"max" => 1,
			"url" => array("add")
		), function() {
			save(array("posts", "option"));
			$this->permission = array("posts" => array("insert" => array("public")));
		} );

		$this->listen(array(
			"max" => 2,
			"url" => array("edit" , "/^\d+$/")
		), function() {
			save(array("posts", "option"));
			$this->permission = array("posts" => array("update" => array("public")));
		});
		$this->listen(array(
			"max" => 2,
			"url" => array("group")), 
		function () {
			save(array("posts", "group"));
			// $this->permission = array("posts_group")
		});
		$this->listen(array(
			"max" => 1,
			"url" => array("/^\d+$/")), 
		array("posts"));

	}
}
?>