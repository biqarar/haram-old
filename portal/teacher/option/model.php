<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */
class model extends main_model {

	public function makeQuery() {
		//------------------------------  make sql object
		return $this->sql()->tableBridge()
			->setUsers_id(post::users_id())
			->setTitle(post::title())
			->setValue(post::value())
			->setDescription(post::description());
	}

	public function post_add_bridge() {
		//------------------------------ insert bridge
		$sql = $this->makeQuery()->insert();

		// BUG ------------ BUG  ------------ BUG  ------------ BUG  ------------ BUG  ------------ BUG 
		// IF usersid SET IN URL NOT FOUNT THIS PLACE !!! WHY ????
		// print_r($sql->string());
		// die();
		
		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[insert bridge successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[insert bridge failed]]");
		});
	}

	public function post_edit_bridge() {

		//------------------------------ update bridge
		$sql = $this->makeQuery()->whereId($this->xuId())->update();

		//------------------------------ commit code
		$this->commit(function() {
			debug_lib::true("[[update bridge successful]]");
		});

		//------------------------------ rollback code
		$this->rollback(function() {
			debug_lib::fatal("[[update bridge failed]]");
		});
	}

	public function sql_list_bridge($users_id = false) {
		return $this->sql()->tableBridge()->whereUsers_id($users_id)->select()->allAssoc();
	}
}
?>