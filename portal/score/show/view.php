<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config() {

		//------------------------------ global
		$this->global->page_title ="show score classes";
		$classesid = $this->xuId("classesid");

		//------------------ check branch
		$this->sql(".branch.classes", $classesid);

		$this->data->score_type_list = $this->sql(".scoreTypeList", $this->xuId("classesid"));
		// var_dump($this->data->score_type_list);exit();
		$this->classesDetail();
		
		//------------------------------ list of score saved in database
		$this->data->dataTable = $this->dtable("score/show/status=apilist/classesid=$classesid/",
			$this->replase($this->sql("#field_list", $classesid)));

	}

	/**
	*	remove 'users_id' from field name 
	* 	it use in dtable in mode.php
	*/
	public function replase($field_list = false) {
		$return = array();
		array_push($return, "username", "name", "family");
		foreach ($field_list as $key => $value) {
			if(preg_match("/^users\_\id\s(.*)$/", $value)){
				array_push($return, preg_replace("/^users\_\id\s(.*)$/", "$1", $value));
			}
		}
		array_push($return, "certification");
		return $return;
	}
}
?>