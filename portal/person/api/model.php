<?php 
/**
* 
*/
class model extends main_model
{
	public function post_list() {
var_dump("useless");exit();
		$srep = str_replace(" ", "_", $this->xuId("search"));
		$search = $xsearch = array("name", "family", "father");
		foreach ($xsearch as $key => $value) {
			$xsearch[$key] = "IFNULL($value, '')";
		}
		$xsearch  = join($xsearch, ', ');
		$sql = $this->sql()->tablePerson();

		$split = preg_split("[\s]", $this->xuId("search"), 3);
		$sql->groupOpen();
		foreach ($split as $key => $value) {
			if($key === 0){
				$sql->condition("where", "##{$search[$key]}", "LIKE", "%$value%");
			}else{
				$sql->condition("AND", "##{$search[$key]}", "LIKE", "%$value%");
			}
		}
		$sql->groupClose();

		$sql->condition("or" , "##concat($xsearch)" , "like" , "%" . $this->xuId("search") . "%");
		
		// $sql->fieldName("name")->fieldFamily("family")->fieldFather("father")->fieldUsersid("id");

		$sql->joinUsers()->whereId("#person.users_id")->fieldUsername();
		$sql->condition("or", "##users.username" , "like" ,$this->xuId("search")."%");
		
		$sql = $sql->limit(10)->select();
		$array = array();

		foreach ($sql->allAssoc() as $key => $value) {
			$array[] = array(
				"value" => $value['name'].' '.$value['family'] . ' ' . $value['father'],
				"id" =>  $value['users_id'],
				"person_id" =>  $value['id']

				);
		}
		
		debug_lib::msg("list", $array);
	}
}
?>