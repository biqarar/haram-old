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
		if($this->xuId("classificationid")){
			
			$classificationid = $this->xuId("classificationid");

			$this->sql(".branch.classification", $classificationid);


			$retest = $this->sql("#retest", $classificationid);

			$this->data->retest = isset($retest['list']) ? $retest : false;

		}
	
		$this->data->dataTable = $this->dtable(
			"users/learn/score/status=apilist/id=" . $this->xuId() . "/", 
			array("id", "plan", "teachername","teacherfamily","ریز نمرات","امتیاز نهایی"));
	}
} 
?>