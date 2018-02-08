<?php

class model extends main_model {
	public function post_api() {
		$dt = $this->dtable->table('plan')->fields(
				"group_id",
				"name",
				"price",
				"absence",
				"absence_type",
				"certificate",
				"mark",
				"min_person",
				"max_person",
				"meeting_no",
				"status",
				"type",
				"id chart",
				"id edit")
		->search_fields("name", "price")
		->result(function($r){
			$r->edit = '<a class= "icoedit" href="plan/status=edit/id='. $r->edit . '"></a>';
			$r->chart = $this->tag("a")->href("report/plan/progress/id=". $r->chart)->class("icoscore")->render();
		})
		->search_result(function($result){
			
			$vsearch = $_POST['search']['value'];
			$vsearch = str_replace(" ", "_", $vsearch);
			$result->groupOpen();
			$result->condition("and", "plan.name", "LIKE", "'%$vsearch%'");
			$result->condition("or", "plan.price", "LIKE", "'%$vsearch%'");
			$result->groupClose();
			// echo $resultس->select()->string();exit();

		})
		->query(function ($q){
			//---------- get branch id in the list
			$q->groupOpen();
			foreach ($this->branch() as $key => $value) {
				if($key == 0){
					$q->condition("where", "plan.branch_id","=",$value);
				}else{
					$q->condition("or","plan.branch_id","=",$value);
				}
			}

			$q->groupClose();
		});
		$this->sql(".dataTable", $dt);
	}

	function post_apisection() {
		$dt = $this->dtable->table('plan_section')->fields(
				"id",
				"plan",
				"section",
				"id edit")
		->search_fields("section", "plan.name")
		->query(function($q) {
			$q->joinPlan()->whereId("#plan_section.plan_id")->fieldName("plan");
		})
		->result(function($r){
			$r->edit = '<a class= "icoedit" href="plan/section/status=edit/id='. $r->edit . '"></a>';
			
		});
		$this->sql(".dataTable", $dt);

	}
}

?>