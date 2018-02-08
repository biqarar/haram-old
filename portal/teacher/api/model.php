<?php 
/**
* 
*/
class model extends main_model
{
	public function post_list() {

		$sql = $this->sql()->tablePerson()
		->condition("or" , "##concat(name, family, father)" , "like" , "%" . $this->xuId("search") . "%");
		// $sql->fieldName("name")->fieldFamily("family")->fieldFather("father");//->fieldUsersid("id");
// 
		$sql->joinUsers()->whereId("#person.users_id")->fieldUsername();
		$sql->joinUsers_branch()->whereUsers_id("#users.id")->andType("teacher");

		//---------- get branch id in the list
		$sql->groupOpen();
		foreach ($this->branch() as $key => $value) {
			if($key == 0){
				$sql->condition("and", "users_branch.branch_id","=",$value);
			}else{
				$sql->condition("or","users_branch.branch_id","=",$value);
			}
		}
		$sql->groupClose();
		// $sql->condition("or", "##users.username" , "=" ,$this->xuId("search"));
		
		$sql = $sql->limit(10)->select();
		$array = array();
		// echo $sql->string();exit();/
		// ilog($sql->string());
		foreach ($sql->allAssoc() as $key => $value) {
			$array[] = array(
				"value" => $value['name'].' '.$value['family'],
				"id" =>  $value['users_id']

				);
		}
		
		debug_lib::msg("list", $array);

	}
}
?>