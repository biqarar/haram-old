<?php
class model extends main_model {


	/**
	*	list of field
	* 	whit select score type of plan
	*/
	public function sql_field_list($classesid = false) {

		//---------------- check branch
		$this->sql(".branch.classes", $classesid);

		$calculation = $this->sql()->tableClasses()->whereId($classesid)->fieldId();
		$calculation->joinPlan()->whereId("#classes.plan_id")->fieldId();
		$calculation->joinScore_type()->wherePlan_id("#plan.id")->andStatus('enable')->fieldTitle();
		$list = $calculation->select()->allAssoc();

		$field_list = array("username users.username", "pname person.name", "family person.family");

		foreach ($list as $key => $value) {
			array_push($field_list, "users_id " . $value['title']);
		}

		array_push($field_list, "users_id score");
		array_push($field_list, "id certification");

		return $field_list;
	}

	public function post_api() {

		$classesid = $this->xuId("classesid");

		//---------------- check branch
		$this->sql(".branch.classes", $classesid);


		$list = $this->sql(".scoreCalculation.score_classes",$classesid);
		// print_r($list);exit();

		$dtable = $this->dtable->table("classification")
			->fields($this->sql_field_list($classesid))
			->search_fields("username", "name person.name", "family person.family")
			->query(function($q){

				$q->andClasses_id($this->xuId("classesid"));
				$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldName("pname")->fieldFamily("family");
				$q->joinUsers()->whereId("#classification.users_id")->fieldUsername("username");

			})
			->search_result(function($result){
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->condition("and", "##concat(person.name, person.family)", "LIKE", "%$vsearch%");
			})
			->result(function($r, $list){
				$r->certification = $this->check_certification($r->certification, $r->score);
				if(is_array($list) && !empty($list)){

					foreach ($list as $key => $value) {
						foreach ($value['title'] as $k => $v) {
							$x = $r->users_id;
							$r->$v = isset($list[$x]['value'][$k]) ? $list[$x]['value'][$k] : "-";
						}
						break;
					}
					$x = $r->users_id;
					$r->score = isset($list[$x]['result']) ? $list[$x]['result'] : "-" ;
				}
			}, $list);

			$this->sql(".dataTable", $dtable);
	}

	function check_certification($classification_id = false, $urser_id = false) {
		// return "f";
		$check = $this->sql()->tableCertification()->whereClassification_id($classification_id)->limit(1)->select()->num();
		if($check == 0 ) {
			return $this->tag("a")->href("users/learn/id=". $urser_id)->title("نمایش پرونده تحصیلی فراگیر")->class("icocertification")->render();
		}else{
			return $this->tag("a")->href("users/learn/id=". $urser_id)->title("نمایش پرونده تحصیلی فراگیر")->class("icocertificationdisable")->render();
		}
	// `$list_certification = $this->sql(".findListCertification.classes", $classification_id);

	}

}
?>