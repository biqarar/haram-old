<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config() {

		//------------------------------ global
		$this->global->page_title ="score";
		$classesid = $this->xuId("classesid");

		//----------------- check branch
		$this->sql(".branch.classes", $classesid);

		$this->data->score_type_list = $this->sql(".scoreTypeList", $classesid);

		//------------------------------ get detail classes
		$this->classesDetail();
	}
}
?>