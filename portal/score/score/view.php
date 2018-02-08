<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config() {
		

		//------------------------------ global
		$this->global->page_title ="score";
		
		$this->data->msg = false;

		//----------------- check branch
		$this->sql(".branch.score_type", $this->xuId("scoretypeid"));
		$this->sql(".branch.classes", $this->xuId("classesid"));

		$this->global->score_type = $this->sql("#score_type", $this->xuId("scoretypeid"), "title");

		$this->data->score_type_list = $this->sql(".scoreTypeList", $this->xuId("classesid"));

		$this->classesDetail();

		if($this->data->list['list'][0]['status'] == "done") {
			$this->data->msg = "این کلاس به اتمام رسیده است.";
		}else{
			$this->data->dataTable = $this->dtable(
				"score/classes/status=apilist/classesid=" . $this->xuId("classesid")
				.'/scoretypeid=' . $this->xuId("scoretypeid") . "/",
				array("more", "username","name", "family","آزمون مجدد", "امتیاز " . $this->global->score_type,  "نمایش"));
			$this->data->classes_id = $this->data->list['list'][0]['id'];
		}
	}
}
?>