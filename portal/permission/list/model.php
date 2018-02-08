<?php 
class model extends main_model {
	public function post_api() {
		
		$dtable = $this->dtable->table("permission")
		->fields(
			"id",
			"username",
			"name",
			"family",
			 "users_branch_id",
			 "tables",
			 "select",
			 "insert",
			 "update",
			 "delete",
			 "condition",
			 "id edit",
			 "id delete_")
		->search_fields(
			"username users.username",
			"name",
			"family",
			"tables")
		->order(function($q, $b, $n){
			if($b == "orderUsername"){
				$q->join->users->orderUsername($n);
			}elseif($b == "orderName"){
				$q->join->person->orderName($n);
			}elseif($b == "orderFamily"){
				$q->join->person->orderFamily($n);
			}else{
				return true;
			}
		})
		->query(function($q){
			$q->joinUsers_branch()->whereId("#permission.users_branch_id")->fieldId("users_branch_id");
			$q->joinUsers()->whereId("#users_branch.users_id")->fieldUsername("username")->fieldId("users_id");
			$q->joinPerson()->whereUsers_id("#users.id")->fieldName("name")->fieldFamily("family")->fieldId("person_id");
			// var_dump($q->select()->string());
		})

		->result(function($r) {
			// var_dump($r);exit();
			$r->edit = '<a class="icoedit" href="permission/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
			$r->delete_ = $this->tag("a")->class("icoredclose deletepermission")->value($r->delete_)->href("")->render();
		});
		$this->sql(".dataTable", $dtable);
	}

}
?>
