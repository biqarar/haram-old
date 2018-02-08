<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
		public $dateNow = false;
		public function post_api(){
		$this->dateNow = $this->dateNow();
		$dtable = $this->dtable->table("certification")
		->fields(
			"usersid learn",
			"id id",
			"username username",
			"name person.name",
			"family person.family",
			"planname",
			"mark",
			"mark xmark",
			"date_request date_request",
			"date_print date_print",
			"date_deliver date_deliver"
		)
		->order(function($q, $n, $b){
			if($n === 'orderUsername'){
				$q->join->users->orderUsername($b);
			}else{
				return true;
			}
		})
		->search_fields(
			"id certification.id","username" , "family", "date_request",
			"date_print",
			"date_deliver"
		)
		->query(function($q){
			$q->joinClassification()->whereId("#certification.classification_id")->fieldMark("mark");
			$q->joinClasses()->whereId("#classification.classes_id")->fieldId();
			$q->joinPlan()->whereId("#classes.plan_id")->fieldName("planname");
			$q->joinUsers()->whereId("#classification.users_id")->fieldUsername("username")->fieldId("usersid");
			$q->joinPerson()->whereUsers_id("#classification.users_id")->fieldFamily("family")->fieldName("name");
			foreach ($this->branch() as $key => $value) {
				if($key == 0){
					$q->condition("and", "plan.branch_id","=",$value);
				}else{
					$q->condition("or","plan.branch_id","=",$value);
				}
			}

		})
		->result(function($r){

			$r->xmark = $this->sql(".certification", $r->xmark);

			if($r->date_print == null) {
				$r->date_print = $this->tag("a")->title("ثبت تاریخ چاپ")->style("cursor: pointer;")->certificationid($r->id)->class("icocalendar set-date-print")->dateNow($this->dateNow)->render();
			}else{
				$r->date_print = $this->tag("a")->title("حذف تاریخ چاپ")->style("cursor: pointer;")->certificationid($r->id)->class("delete-date-print")->dateNow($this->dateNow)->vtext($r->date_print)->render();
			}

			if($r->date_deliver == null) {
				$r->date_deliver = $this->tag("a")->title("ثبت تاریخ تحویل")->style("cursor: pointer;")->certificationid($r->id)->class("icocalendar set-date-deliver")->dateNow($this->dateNow)->render();
			}else{
				$r->date_deliver = $this->tag("a")->title("حذف تاریخ تحویل")->style("cursor: pointer;")->certificationid($r->id)->class("delete-date-deliver")->dateNow($this->dateNow)->vtext($r->date_deliver)->render();
			}
			$r->learn = $this->tag("a")->href("users/learn/id=". $r->learn)->class("icoshare")->render();
		});

		$this->sql(".dataTable", $dtable);
	}	
}
?>