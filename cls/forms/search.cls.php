<?php
class forms_login_cls extends forms_lib{
	function __construct(){
		$hidden = $this->form("#hidden")->value("xsearch");
		$searchF = $this->form("text")->name("search")->label("search");
		$submit = $this->form("submit")->value("search");
		$search = array();
		$search[] = $hidden->compile();
		$search[] = $searchF->compile();
		$search[] = $submit->compile();
	}
}
?>