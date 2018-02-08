<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config() {

		//------------------------------ globals
		$this->global->page_title ="return classes";

		$this->global->because = _($this->sql(".classification.detail","because", $this->xuId()));
		$this->global->date_delete = $this->sql(".classification.detail","date_delete", $this->xuId());
		
		if($this->global->date_delete == ''){
			$this->redirect("classification/status=edit/id=". $this->xuId());
		}

		$this->global->classification_id = $this->xuId();

		$this->global->classesid = $this->sql(".classification.detail","classes_id", $this->xuId());
		$this->global->usersid = $this->sql(".classification.detail","users_id", $this->xuId());

		//------------------------------ load form
		$f = $this->form("@classification", $this->urlStatus());
		$f->remove("plan_section_id,mark");
		
	}
}
?>