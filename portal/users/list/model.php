<?php
/**
*
*/
class model extends main_model {
	public function post_api(){
		$type = $this->xuId("type");

		// $this->branch();
		// $type = isset($type) && $type != "" ? $type : "learn";
			// var_dump($type);exit();
		$dtable = $this->dtable->table('person')
			->fields(
				'username',
				'name',
				'family',
				'father',
				'birthday',
				// 'gender',
				'nationalcode',
				'code',
				// 'marriage',
				// 'education_id',
				'users_id detail',
				'users_id learn',
				'users_id')
			->order(function($q, $n, $b){
				if($n === 'orderUsername'){
					$q->join->users->orderUsername($b);
				}else{
					return true;
					$q->orderId($b);
				}
			})
			->search_fields('name', 'family', 'father' , "username users.username" , "nationalcode person.nationalcode")
			->query(function($q){
				$q->joinUsers()->whereId("#person.users_id")->fieldUsername("username");
				$q->joinUsers_branch()->whereUsers_id("users.id");
				$q->groupOpen();
				//---------- get branch id in the list
				foreach ($this->branch() as $key => $value) {
					if($key == 0){
						$q->condition("and", "users_branch.branch_id","=",$value);
					}else{
						$q->condition("or","users_branch.branch_id","=",$value);
					}
				}
				$q->groupClose();
				// echo ($q->select()->string());exit();
			})
			->search_result(function($result){
				$search = array('name', "username users.username" , "nationalcode person.nationalcode");

				foreach ($search as $key => $value) {
					if(preg_match("/^[^\s]*\s(.*)$/", $value, $nvalue)){
						$search[$key] = $nvalue[1];
					}
				}
				// var_dump($_POST['search']['value']);
				$vsearch = $_POST['search']['value'];
				$vsearch_split = preg_split("/\s{2}/", $vsearch);

				$public = null;
				if(isset($vsearch_split[0]))
				{
					$public = trim($vsearch_split[0]);
				}

				$family = null;
				if(isset($vsearch_split[1]))
				{
					$family = trim($vsearch_split[1]);
				}

				$father = null;
				if(isset($vsearch_split[2]))
				{
					$father = trim($vsearch_split[2]);
				}
				$result->groupOpen();

				if($public && !$family && !$father)
				{

					$result->condition("and", "users.username", "LIKE", "'%$public%'");
					$result->condition("or", "person.nationalcode", "LIKE", "'%$public%'");
					$result->condition("or", "person.name", "LIKE", "'%$public%'");
					$result->condition("or", "person.family", "LIKE", "'%$public%'");
					$result->condition("or", "person.father", "LIKE", "'%$public%'");
				}
				else
				{
					$result->condition("and", "person.name", "LIKE", "'%$public%'");
				}

				if($family)
				{
					$result->condition("and", "person.family", "LIKE", "'%$family%'");
				}

				if($father)
				{
					$result->condition("and", "person.father", "LIKE", "'%$father%'");
				}

				$result->groupClose();

				// echo ($result->select()->string());
				// exit();

				// $ssearch = preg_split("[ ]", $vsearch);
				// $vsearch = str_replace(" ", "_", $vsearch);
				// $csearch = $search;
				// foreach ($search as $key => $value) {
				// 	$search[$key] = "IFNULL($value, '')";
				// }
				// $result->condition("and", "##concat($search)", "LIKE", "%$vsearch%");
				// foreach ($csearch as $key => $value) {
				// 	if(isset($ssearch[$key])){
				// 		$sssearch = $ssearch[$key];
				// 		if($key === 0){
				// 			$result->condition("OR", "##$value", "LIKE", "%$sssearch%");
				// 		}else{
				// 			$result->condition("AND", "##$value", "LIKE", "%$sssearch%");
				// 		}
				// 	}
				// }
				//
				// ////////////////////////////////////
				//
				// $vsearch = $_POST['search']['value'];
				// // var_dump($_POST['search']['value']);exit();
				// $vsearch = str_replace(" ", "", $vsearch);
				// $result->condition("and", "##concat(IFNULL(person.name, ''), IFNULL(person.family, ''), IFNULL(person.father, ''))", "LIKE", "'%$vsearch%'");
				// // $result->groupOpen();
				// $result->condition("or", "users.username", "LIKE", "'%$vsearch%'");
				// $result->condition("or", "person.nationalcode", "LIKE", "'%$vsearch%'");
				// // $result->groupClose();
				// print_r($result->select()->string());exit();
				// // $result->condition("or" "#person.s", "LIKE" "%$vsearch%");
			})
			->result(function($r, $type){
				$r->learn = $this->type($type , $r->learn);
				$r->detail = $this->tag("a")->addClass("icomore")->href("users/status=detail/id=". $r->detail)->render();
			}, $type);
		$this->sql(".dataTable", $dtable);
	}

	public function type($type = false, $id = false) {
		// var_dump($type, $id);exit();
		switch ($type) {
			case 'price':
				return $this->tag("a")
				->addClass("icoprice")->href('price/status=add/usersid='. $id)->render();
				break;
			case 'branch':
				return $this->tag("a")
				->addClass("icosettings")->href('branch/status=change/usersid='. $id)->render();
				break;

			case 'hefz_teams':
			return $this->tag("a")
			->addClass("icodadd")->style("cursor: pointer;")->addClass("add-hefz-teams-users")
				->hefzteamsid($this->xuId("teamid"))->usersid($id)->render();
			break;

			default:
				return $this->tag("a")->addClass("icoshare")->href('users/learn/id='. $id)->render();
				break;
		}
	}
}
?>