<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */

class controller extends main_controller{

	public function config() {

		//----------------------------- drafts
		$this->listen(array(
			"max" => 3,
			"url" => array("send") 
			), function(){
			save(array("sms","send"));

			$this->permission = array("sms" => array("insert" => array("public")));
		});
		//----------------------------- drafts
		$this->listen(array(
			"max" => 3,
			"url" => array("drafts", "status" => "apilist") 
			), function(){
			save(array("sms","drafts", "mod" => "list"));

			$this->permission = array("drafts" => array("select" => array("public")));
		});
		//----------------------------- drafts
		$this->listen(array(
			"max" => 3,
			"url" => array("drafts", "status" => "add") 
			), function(){
			save(array("sms","drafts"));
			$this->permission = array("drafts" => array("insert" => array("public")));
		});

		//----------------------------- drafts
		$this->listen(array(
			"max" => 3,
			"url" => array("drafts", "status" => "edit", "id" => "/^\d+$/")
			), function(){
			save(array("sms","drafts"));
			$this->permission = array("drafts" => array("update" => array("public")));
		});



	}
}
