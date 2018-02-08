<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{


	public function config(){

		//------------------------------ global
		$this->global->page_title = "classes";
		
		$type = $this->xuId("type");
		if(!isset($type) || $type == "") $type = "classification";

		$this->data->dataTable = $this->dtable("classes/status=api/type=$type/"
			, array(
				"id",
				"plan",
				_("name") . ' ' . _("teacher"),
				_("family") . ' ' . _("teacher"),
				"place",
				"age_range",
				"start_time",
				"end_time",
				//"week_days",
				"name",
				"count",
				"$type",
				"بیشتر"
				));
	
	}
}
?>