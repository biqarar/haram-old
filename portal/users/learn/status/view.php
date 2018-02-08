<?php 
/**
 * 
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = _("وضعیت پرونده") . " " . _("student");
		////////////////////////////////////////////////
				//------------------------------ list of branch
		$this->data->dataTable = $this->dtable(
			"users/learn/status/status=apilist/id=" . $this->xuId() . "/", 
			array("id", "plan", "teachername","teacherfamily","date_entry","date_delete","because", "تعداد غیبت", "امتیاز نهایی"));
	}
} 
?>