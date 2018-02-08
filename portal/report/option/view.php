<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'report';

		$f = $this->form("@report", $this->urlStatus());
		//------------------------------ make list of table whit .sql.php file in sql folder 
		$tables = scandir(sql);

		//------------------------------ other permission (no table)
		$other_permission = array(
			"teacher",
			"home"
		);

		//------------------------------ push other permission to list of permission
		foreach ($other_permission as $key => $value) {
			array_push($tables, $value);
		}

		//------------------------------ this table not sohw in table list (system table)
		$black = array(
		".",
		"..",
		"permission",
		"history",
		"login_counter",
		"dev",
		"branch_users_key",
		"branch_cash",
		"setup",
		"ahmad");
		

		foreach ($tables as $key => $value) {
		
			$value = preg_replace("/\.sql\.php$/", "", $value);
		
			if(preg_grep("/^$value$/", $black)) continue;
		
			$f->tables->child()->name("table_" . $value)->label(_($value))->value($value);
		}


		//------------------------------ load form
// var_dump($fclose(handle));
		//------------------------------ list of report
		$this->data->dataTable = $this->dtable(
			"report/status=api/", 
			array("id", "table", "name", "url", "edit"));

		// $this->data->list = $list->compile();
		//------------------------------ edit form
		$this->sql(".edit", "report", $this->xuId(), $f);
	}

}
?>