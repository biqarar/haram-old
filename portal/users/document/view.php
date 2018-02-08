<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{
	public function config(){
		$this->global->title = "اسناد های دریافتی";
		$f = $this->form("@document");

	}
}


class forms extends forms_lib {
	function document(){
		$this->input = $this->make("#hidden")->value("document_add");
		$this->description = $this->make("#description")->name("description")->label("توضیحات");
		$this->submit = $this->make("#submit");
	}
}
?>