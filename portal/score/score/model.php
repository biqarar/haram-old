<?php
class model extends main_model {

	public function sql_score_type($scoretypeid = false, $title = null) {

		//----------------- check branch
		$this->sql(".branch.score_type", $scoretypeid);
		
		if (!$scoretypeid) $scoretypeid = $this->xuId("scoretypeid");
		return $this->sql()->tableScore_type()->whereId($scoretypeid)->limit(1)->select()->assoc($title);
	}
	public function post_api() {

		$score_type = $this->sql_score_type();
		$array = array("usersid more","username users.username","name person.name", "family person.family","id input");
		
		
		$dtable = $this->dtable->table("classification")
			->fields($array)
			->search_fields("name person.name", "family person.family")
			->query(function($q){

				//--------------- check branch
				$this->sql(".branch.classes", $this->xuId("classesid"));
				
				$q->andClasses_id($this->xuId("classesid"));

				$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldName("name")->fieldFamily("family");

				$q->joinUsers()->whereId("#classification.users_id")->fieldUsername()->fieldId("usersid");

			})
			->search_result(function($result){
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->condition("and", "##concat(person.name, person.family)", "LIKE", "%$vsearch%");
			})
			->result(function($r){
				$r->input ="<div class='form-element' >" .  $this->tag("input")
									  ->type("text")
									  ->classificationid($r->input)
									  ->scoretypeid($this->xuId("scoretypeid"))
									  ->addClass('score-mark')
									  ->value($this->get_value($r->input, $this->xuId("scoretypeid")))
									  ->render() . "</div>";

				$r->more = $this->tag("a")->class("icomore")->href("users/status=detail/id=". $r->more)->render();
			});
			$this->sql(".dataTable", $dtable);
	}

	public function get_value($classificationid = false, $scoretypeid = false) {
		$check = $this->sql()->tableScore()
					->whereClassification_id($classificationid)
					->andScore_type_id($scoretypeid)->limit(1)->select();
					// var_dump($check->assoc("value")); exit();
			return $check->assoc("value");
		if($check->num() == 1) {
		}else{
			return;
		}
	}
}
?>