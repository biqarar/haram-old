<?php 
class query_olddb_cls extends query_cls {
	public function config($users_id = false) {
		//------------------------------  make olddb card
		$query_olddb = $this->sql_olddb($users_id);
		if(!empty($query_olddb)){
			$olddb = array();
			$i = 1;
			foreach ($query_olddb as $key => $arrayValue) {
				if($key != "student"){
					$c = "&nbsp&nbspتعداد&nbsp&nbsp";
					$m = "&nbsp&nbspمورد&nbsp&nbsp";
				}else{
					$c = "";	
					$m = "";
				}
				$olddb["list"]['list'][0][$key] =
				 "<a style='text-decoration: none' >".$c .  $arrayValue . $m ."&nbsp&nbsp&nbsp&nbsp</a>"
				// ->style("text-decoration: none")
				."<a style='text-decoration: none' href='olddb/" .$key . '/id='. $query_olddb['student'] . "'>نمایش کامل اطلاعات</a>";
				$i++;
			}
			//------------------------------  make global of olddb card
			$olddb['title'] = "olddb";
			// $olddb["moreLink"] = "olddb/status=detail/usersid=$users_id";
			return $olddb;
		}
	}

	public function sql_olddb($users_id = false) {
		
		// $old_casecode = $this->sql()->tableStudent()->whereUsers_id($users_id)->limit(1)->select();
		$old_casecode = $this->db("SELECT * FROM `quran_hadith_old`.`student` WHERE `users_id` LIKE '$users_id' LIMIT 0,1");

		if($old_casecode->num() >= 1) {
			
			$old_casecode       = $old_casecode->assoc("name1");
			
			$old_price    	    = $this->db("SELECT * from `quran_hadith_old`.`oldprice` 
											 WHERE `parvande` LIKE '$old_casecode'")->num();
			
			$old_classification = $this->db("SELECT * from `quran_hadith_old`.`oldclassification` 
											 WHERE `parvande` LIKE '$old_casecode'")->num();

			$old_certification = $this->db("SELECT * from `quran_hadith_old`.`oldcertification` 
											WHERE `parvande` LIKE '$old_casecode'")->num();

			return  array(
				"student"		 => $old_casecode,
				"classification" => $old_classification,
				"price"			 => $old_price,
				"certification"  => $old_certification
			);
		}
	}
}

?>