<?php
class model extends main_model {
	public function post_api() {
		$dtable = $this->dtable->table("classification")
			
			->fields(
			"usersid more",
			"username users.username",
			"name person.name",
			"family person.family",
			"date_entry",
			"date_delete",
			"because",
			"mark",
			"id edit",
			"usersid")
			
			->search_fields("username", "name", "family")
			->query(function($q){

				//------------ check branch
				$this->sql(".branch.classes",$this->xuId("classesid"));
				
				$q->andClasses_id($this->xuId("classesid"));
				$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldName("name")->fieldFamily("family");
				$q->joinUsers()->whereId("#classification.users_id")->fieldUsername("username")->fieldId("usersid");
			
			})
			->search_result(function($result){
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->condition("and", "##concat(person.name, person.family)", "LIKE", "%$vsearch%");
			})
			->result(function($r){
				// $r->usersid = "sss";
				if($this->check_classification($r->edit)){
					$r->edit = $this->tag("a")->addClass("icoredclose")->href("classification/status=edit/id=". $r->edit)->render();
				}else{
					$r->edit = $this->tag("a")->addClass("icodadd")->href("classification/returnclasses/id=". $r->edit)->render();
				}
					$r->more = $this->tag("a")->addClass("icoshare")->href("users/learn/id=" . $r->more)->render();
				// var_dump($r->more);exit();
				// $r->edit = "fff";
			});
			$this->sql(".dataTable", $dtable);
	}
	function check_classification($classificationid = false) {
		$check_classification = $this->sql()
		->tableClassification()
		->whereId($classificationid)
		->limit(1)->select()->assoc();
		if($check_classification['date_delete'] == "" and $check_classification['because'] == ""){
			return true;
		}else{
			return false;
		}
	}

}
?>