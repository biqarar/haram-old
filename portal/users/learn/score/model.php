<?php 
/**
* 
*/
class model extends main_model {

	public function sql_retest($classificationid = false) {
		$log = $this->sql()->tableLogs()
		->condition("where", "log_meta" , "like", "'scoreretest/classificationid=" . $classificationid . "%'")
		->select();
		$return = array();
		$return['title'] = "آزمون های مجدد ثبت شده";
		$return['list']['list'][0]['امتیاز فعلی'] = $this->find_score($classificationid);
		if($log->num() > 0) {
			foreach ($log->allAssoc() as $key => $value) {
				$score_type_id = preg_match("/^scoreretest\/classificationid\=(\d+)\/scoretypeid\=(\d+)$/", $value['log_meta'], $c);
				$score_type_id = $c[2];
				$title = ++$key . "- آزمون " . $this->get_score_type_name($score_type_id);
				
				$val =  $value['log_data'];
	
				$return["list"]['list'][0][$title] = $val;

			}
		}

		return $return;
	}

	public function get_score_type_name($score_type_id = false ){
		return $this->sql()->tableScore_type()->whereId($score_type_id)->limit(1)->select()->assoc("title");
	}
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
			$r->score = $this->tag("a")->vtext($this->find_score($r->score))
			->href("users/learn/score/id=".$this->xuId()."/classificationid=". $r->score)->title("نمایش ریز نمرات و آزمون های ")->render();
		});
		$this->sql(".dataTable", $dtable);
	}

	public function find_score($classificationid = false) {

		$this->sql(".branch.classification", $classificationid);
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