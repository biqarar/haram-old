<?php 
/**
* 
*/
class model extends main_model {

	public function post_api(){
		$usersid = $this->xuId();
			//----------------------- check banch
		$this->sql(".branch.users",$usersid);

		$dtable = $this->dtable->table("classification")
		->fields("id", "plan", "teachername","teacherfamily","id score","mark mark")
		->search_fields("plan", "teacher")
		->query(function($q){
			$q->whereUsers_id($this->xuId());
			$q->joinClasses()->whereId("#classification.classes_id")->fieldId("classesid");
			$q->joinPlan()->whereId("#classes.plan_id")->fieldName("plan");
			$q->joinPerson()->whereUsers_id("#classes.teacher")->fieldName("teachername")->fieldFamily("teacherfamily");
			// $q->joinScore()->whereClassification_id("#classification.id");
		})
		->result(function($r) {
			// $r->absence = $this->tag("a")->href("users/learn/absence/id=" . $this->xuId())->vtext($this->find_count_absence($r->absence))->render();
			$r->score = $this->tag("a")->vtext($this->find_score($r->score))->render();
		});
		$this->sql(".dataTable", $dtable);
	}

	public function find_score($classificationid = false) {
		$score = $this->sql()->tableScore()->whereClassification_id($classificationid);
		$score->joinScore_type()->whereId("#score.score_type_id");
		$score = $score->select()->allAssoc();
		$all = "";
		foreach ($score as $key => $value) {
			$all .= " ( " . $value['title'] . " = " . $value['value'] . " ) ";
		}
		return $all;
		// var_dump($score);exit();
	}
}
?>