<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'Profile';

	// var_dump($_SESSION['my_user']['permission']);exit();
		//------------------------------ globals type
		$this->global->type = isset($_SESSION['my_user']['type']) ? $_SESSION['my_user']['type'] : "";

		//------------------------------ globals (name & family)
		$this->global->name = isset($_SESSION['my_user']['name']) ? $_SESSION['my_user']['name'] : "";

		$this->global->family = isset($_SESSION['my_user']['family']) ? $_SESSION['my_user']['family'] : "";

		//------------------------------ globals (gender)
		if(isset($_SESSION['my_user']['gender'])){

			$this->global->gender = ($_SESSION['my_user']['gender']  == "male") ? _("Mr.") : _("Mrs.");

		}

		// var_dump($this->sql("#query"));
	}
}
?>