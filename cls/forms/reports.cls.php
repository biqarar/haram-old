<?php
class forms_reports_cls extends forms_lib{
	function __construct($type = false){
		// $this->username = $this->make("text")->label("نام کاربری")->name("username");
		// $this->password = $this->make("password")->name("password")->label("password");
		// $this->submit = $this->make("#submitlogin")->value("login");
		$this->hidden = $this->make("#hidden")->value("reprot");
		$this->lists = $this->make("select")->name("lists")->label("reports")->addClass("notselect");
		$this->sumbit = $this->make("#submitedit")->value("select");

		// $list = $this->sql(".reports.rList", $type);

		// foreach ($list as $key => $value) {
			// $lists->child()->name($value['tables'])->label($value['name'])->value($value['tables']);
		// }

		
		// $reprot = array();
		
		// $reprot[] = $hidden->compile();
		// $reprot[] = $lists->compile();
		// $reprot[] = $submit->compile();
	}
}
?>