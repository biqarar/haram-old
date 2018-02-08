<?php
class model extends main_model{
	/**
	* return list of city in province
	*/
	public function post_list(){
		
		$search = $this->xuId("search");

		if(preg_match("/(.*)\-(.*)/", $search, $c)){
			$province_name = trim($c[1]);
			$city_name = trim($c[2]);
			
			$sql = $this->sql()->tableCity()->likeName("%".$city_name."%")
				->andlike("##province.name","%".$province_name."%" );
			$sql->joinProvince()->whereId("#city.province_id")->fieldName("pname");
		}else{
			$sql = $this->sql()->tableCity()->likeName("%".$this->xuId("search")."%")->orlike("##province.name","%".$this->xuId("search")."%" );
			$sql->joinProvince()->whereId("#city.province_id")->fieldName("pname");

		}
		
		$r = $sql->select();
		$array = array();
		foreach ($r->allAssoc() as $key => $value) {
			$array[] = array(
				"value" => $value['pname'].' - '.$value['name'],
				"id" =>  $value['id']

				);
		}
		debug_lib::msg("list", $array);
	}
}
?>