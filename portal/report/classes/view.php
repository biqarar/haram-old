<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){
		//------------------------------ global
		$this->global->page_title = "classes";
		$this->global->url = "portal/report/classes";
		
		$hidden = $this->form("#hidden")->value("reports");
		$lists =  $this->form("select")->name("lists")->label("reports")->addClass("notselect");
		$submit = $this->form("button")->value("select")->addClass("start-reports");

		$list = $this->sql(".reports.rList", "classes");

		foreach ($list as $key => $value) {
			$lists->child()->name($value['tables'])->label($value['name'])->value($value['url']);
		}

		
		$reports = array();
		
		$reports[] = $hidden->compile();
		$reports[] = $lists->compile();
		$reports[] = $submit->compile();
		
		$this->data->report_list = $reports;

		$this->data->dataTable = $this->dtable("report/classes/status=apilist/"
			, array(
				"id",
				"plan",
				_("name") . ' ' . _("teacher"),
				_("family") . ' ' . _("teacher"),
				"place",
				"age_range",
				"start_time",
				"end_time",
				"capacity",
				"count",
				"select",
				"detail"
				));
	}
}
?>