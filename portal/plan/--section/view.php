<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {
	public function config() {
		$this->global->page_title = "plan_section";
		// $this->global->url = $this->uStatus(true, true);
		$f = $this->form("@plan_section", $this->urlStatus());

		$this->sql(".edit", "plan_section", $this->xuId(), $f);

		//------------------------------ list of plan
		$this->data->dataTable = $this->dtable(
			"plan/status=apisection/", 
			array(
				"id",
				"plan",
				"section",
				"edit"));
	}
}
?>