<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view{
	public function config(){
		//------------------------------ globals
		$this->global->page_title='courseclasses';

		//------------------------------ locad form
		$f = $this->form("@courseclasses", $this->urlStatus());

		//------------------------------ list of branch
		$this->listBranch($f);

		//------------------------------ remove branch_id because list of branch is loaded
		$f->remove("branch_id");
		
		//-------------------- check branch
		$this->sql(".branch.courseclasses", $this->xuId());
		//------------------------------ edit form
		$this->sql(".edit", "courseclasses", $this->xuId(), $f);

		$this->data->dataTable = $this->dtable("classes/status=api/type=courseclasses/"
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
				"add",
				"detail"
				));

		
	}
}
?>