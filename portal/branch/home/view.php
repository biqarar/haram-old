<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */

class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "branch";

		//------------------------------ edit link
		$c = $this->editLink("branch");

		//------------------------------ list of branch
		$this->data->list = $this->sql(".list","branch")->addColEnd("view", "more")->select(-1, "view")
		->html($c)->compile();
	}
}
?>