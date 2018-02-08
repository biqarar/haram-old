<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/

class view extends main_view {

	public function config() {
		//------------------------------ global
		$this->global->page_title='permission';

		//------------------------------ load form
		$f = $this->form("@permission", $this->urlStatus());

		//------------------------------list of branch
		$this->listBranch($f);

		$f->add("username", "text")->name("username")->label("username")->addClass("username");
		$f->add("showbranch", "button")->name("showbranch")->value("show branch")->addClass("showbranch");

		$f->after("username", "hidden");
		$f->after("branch_id", "username");
		$f->after("showbranch", "username");
		$f->remove("users_branch_id");
		// var_dump($f);exit();
		//------------------------------ make list of table whit .sql.php file in sql folder 
		$tables = scandir(sql);

		//------------------------------ other permission (no table)
		$other_permission = array(
			"teacher",
			"home",
			"report"
		);

		//------------------------------ push other permission to list of permission
		foreach ($other_permission as $key => $value) {
			array_push($tables, $value);
		}

		//------------------------------ this table not sohw in table list (system table)
		$black = array(".","..", "permission", "history", "login_counter", "branch_users_key", "setup","branch_cash", "logs", "update_log");
		

		foreach ($tables as $key => $value) {
		
			$value = preg_replace("/\.sql\.php$/", "", $value);
		
			if(preg_grep("/^$value$/", $black)) continue;
		
			$f->tables->child()->name("table_" . $value)->label($value)->value($value);
		}

		if($this->urlStatus() == "edit") {
			//------------------------------ edit form
			$this->sql(".edit", "permission", $this->xuId(), $f);
			$f->users_id->attr['value'] = $this->sql("#find_username", $f->users_id->attr['value']);
		}
		//------------------------------ list of users pemission
		$this->data->dataTable = $this->dtable(
			"permission/status=api/", 
			array("id",
				"username",
				"name",
				"family",
			 "users_branch_id",
			 "tables",
			 "select",
			 "insert",
			 "update",
			 "delete",
			 "condition",
			 "edit",
			 "delete"));
		
	}
}
?>