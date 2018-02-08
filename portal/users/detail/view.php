<?php 
/**
 * 
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = "users_detail";

		//------------------------------  set users_id
		$users_id  = $this->xuId();

		//--------------- check branch
		$this->sql(".branch.users", $users_id);
		

		$this->topLinks(array(
			array("title" => "آموزش", "url" =>"users/learn/id=$users_id"),
			array("title" => "مشخصات", "url" =>"users/status=detail/id=$users_id"),
				array("title" => "شعبه", "url" =>"branch/status=change/usersid=$users_id")

		));
		//------------------------------ check users (if teacher , can not be display another users by id)
		$this->check_users_type($users_id);

		//------------------------------  make olddb card
		$this->data->olddb = $this->sql(".olddb", $users_id);


		//------------------------------  make person card
		$person = $this->sql(".list.card", "person", $users_id, "users_id");
		// var_dump($person);exit();
		unset($person['addLink']);
		$person['titleLink'] = "users/status=list";
		$person["editLink"] = "person/status=edit/id=" . $person['list']['list'][0]['id'];
		
		$person['list']['list'][0]['from'] = 
			$this->sql(".assoc.foreign", "city", 
					($person['list']['list'][0]['from'] != '') ?
						$person['list']['list'][0]['from'] : 0 , "name");

		$this->data->person = $person;


		//------------------------------  make users card
		$users = $this->sql(".list.card", "users", $users_id, "id");
		unset($users['addLink']);
		unset($users['moreLink']);
		unset($users['list']['list'][0]['password']);
		$users['titleLink'] = "users/status=list";
		$this->data->users = $users;


		//------------------------------  make bridge card
		$bridge = $this->sql("#bridge_detail" , $users_id);
		$new_bridge = array();
		$i = 1;
		foreach ($bridge as $key => $arrayValue) {
			if($i <= 5){
				$new_bridge["list"]['list'][0][$arrayValue['title']] = $arrayValue['value'];
			}
			$i++;
		}

		//------------------------------  make global of bridge card
		$new_bridge['title'] = "bridge";
		$new_bridge["addLink"] = "bridge/status=add/usersid=$users_id";
		$new_bridge["moreLink"] = "bridge/status=detail/usersid=$users_id";
		$new_bridge["editLink"] = "bridge/status=edit/usersid=$users_id";
		$new_bridge['titleLink'] = "bridge/status=list";		
		$this->data->bridge = $new_bridge;


		//------------------------------  make teacher card (person extera)
		$person_extera =  $this->sql(".list.card", "person_extera" , $users_id , "users_id");
		if(isset($person_extera['list']['list'][0])){
			unset($person_extera['addLink']);
			$person_extera["editLink"] = "person/extera/status=edit/usersid=$users_id";
			$person_extera["moreLink"] = "person/extera/status=detail/usersid=$users_id";
			$this->data->person_extera = $person_extera;		
		}




	}
} 
?>