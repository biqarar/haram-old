<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_add_users() {
		//------------------------------  add users by url : person/status=add
	}

	public function post_edit_users() {

		//----------------- check branch
		$this->sql(".branch.users",$this->xuId());
		
		//------------------------------  update users
		$users_sql = $this->sql()->tableUsers()
			->setEmail(post::email())
			// ->setType(post::type())
			// ->setStatus(post::status())
			->whereId($this->xuId())
			->update();

		//------------------------------  commit code
		$this->commit(function(){
			debug_lib::true("[[update users successful]]");
		});

		//------------------------------  roolback code
		$this->rollback(function(){
			debug_lib::fatal("[[update users failed]]");
		});
	}
}
?>