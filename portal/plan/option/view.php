<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{
	
	public function config(){

		//------------------------------ globals
		$this->global->page_title ="plan";

		//------------------------------ load form
		$f = $this->form("@plan", $this->urlStatus());

		//------------------------------ list of branch
		$this->listBranch($f);

		//--------------- check branch
		$this->sql(".branch.plan", $this->xuId());
		
		//------------------------------ edit form
		$this->sql(".edit", "plan", $this->xuId(), $f);
		
		//------------------------------ list of plan
		$this->data->dataTable = $this->dtable(
			"plan/status=api/", 
			array(
				"group_id",
				"name",
				"price",
				"absence",
				"روش محاسبه",
				"certificate",
				"mark",
				"min_person",
				"max_person",
				"meeting_no",
				"ثبت نام ایینترنتی",
				"مجوز ثبت نام",
				"وضعیت",
				"edit"));
	}
}
?>