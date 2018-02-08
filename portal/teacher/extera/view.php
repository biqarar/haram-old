<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */

class view extends main_view
{
	public function config()
{
		//------------------------------ set global
		$this->global->page_title =_("person_extera");

		//------------------------------ check users (if teacher, can not be display by changing id)
		$users_id = $this->SESSION_usersid();

		$this->check_users_type($users_id);

		//------------------------------ url
		$this->global->url = "usersid=" . $users_id;

		//------------------------------ if person extera exist can not be add another record
		$id = $this->sql("#person_extera_id", $users_id);

		//------------------------------ load person_extera form
		$f = $this->form("@person_extera", (!$id) ? "add": "edit");

		$this->sql(".edit", "person_extera", $id , $f);
	}
}
?>