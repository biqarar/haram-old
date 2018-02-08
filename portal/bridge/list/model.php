<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
		public function post_api(){
		
		$dtable = $this->dtable->table("bridge")
		->fields(
			"usersid more",
			"username",
			"name",
			"family",
			"title",
			"value",
			"id edit"
		)
		->order(function($q, $n, $b){
			if($n === 'orderUsername'){
				$q->join->users->orderUsername($b);
			}elseif($n === 'orderName'){
				$q->join->person->orderName($b);
			}elseif($n === 'orderFamily'){
				$q->join->person->orderFamily($b);
			}else{
				return true;
			}
		})
		->search_fields(
			"username" , "value"
		)
		->query(function($q){
			$q->joinPerson()->whereUsers_id("#bridge.users_id")->fieldFamily("family")->fieldName("name");
			$q->joinUsers()->whereId("#bridge.users_id")->fieldUsername("username")->fieldId("usersid");
			
			$q->joinUsers_branch()->whereUsers_id("users.id");
			$q->groupOpen();
			foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("and", "users_branch.branch_id","=",$value);
					}else{
						$q->condition("or","users_branch.branch_id","=",$value);
					}
				}
			$q->groupClose();
				// echo $q->select()->string();exit();
			// ilog($q->select()->string());
		})
		->search_result(function($result){
				
				$vsearch = $_POST['search']['value'];
				$vsearch = str_replace(" ", "_", $vsearch);
				$result->groupOpen();
				$result->condition("and", "users.username", "LIKE", "'%$vsearch%'");
				$result->condition("or", "bridge.value", "LIKE", "'%$vsearch%'");
				$result->groupClose();

		})
		->result(function($r){

			$r->edit = '<a class="icoedit" href="bridge/status=edit/id='.$r->edit.'" title="'.gettext('edit').' '.$r->edit.'"></a>';
			$r->more = $this->tag("a")->href("users/status=detail/id=". $r->more)->class("icomore")->render();
			$r->username = $this->tag("a")->href("users/status=list?username=" . $r->username)
			->target("_blank")->vtext($r->username)->render();
			// // $r->absence = '<a class="icoattendance" href="classification/absence/bridgeid='.$r->absence.'" title="'.gettext('absence').' '.$r->absence.'"></a>';
			// $r->detail = '<a class="icomore" href="bridge/status=detail/id='.$r->detail.'" title="'.gettext('detail').' '.$r->detail.'"></a>';

		});

		$this->sql(".dataTable", $dtable);
	}	
}
?>