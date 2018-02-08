<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {
	public function config() {

		// ------------------------------ globals
		$this->global->page_title = 'users';

		$this->global->url = "status=edit/id=" . $this->xuId();

		// $this->global->url = "status=" . $this->urlStatus();

		// // ------------------------------ load form
		$f = $this->form("@users", $this->urlStatus());

		$f->remove("username,password");


		//--------------- check branch
		$this->sql(".branch.users", $this->xuId());
		//------------------------------ edit person form whit id
		$this->sql(".edit", "users", $this->xuId(), $f);

	}
}

?>