<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */

class view extends main_view{
	public function config(){

		//------------------------------ set global
		$this->global->page_title =_("person_extera");

		//------------------- check branch
		$this->sql(".branch.users", $this->xuId("usersid"));
		
		//------------------------------ list of person extera
		$person_extera = $this->sql(".list", "person_extera", function ($query) {
			$query->whereUsers_id($this->xuId("usersid"));
		})->compile();

		$this->data->list = $person_extera;
	}
}
?>