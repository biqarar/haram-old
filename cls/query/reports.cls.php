<?php
class query_reports_cls extends query_cls
{
	public function rList($tablse = false) {
		$list  = $this->sql()->tableReport()->whereTables($tablse)->select()->allAssoc();
		return $list;
	}

	public function person_classes($classes_id = false, $condition = "id=0") {

		$classification = $this->sql()->tableClassification()->whereClasses_id($classes_id);

		$classification = $this->classification_finde_active_list($classification);
		

		$condition = preg_match("/^(.*)\=(.*)$/", $condition, $where);
	
		$where_ = "and" . ucfirst($where[1]);

		$classification->joinPerson()->whereUsers_id("#classification.users_id")->$where_($where[2]);

		return $classification->select()->num();
	}

	public function classes_average_age($classes_id = false) {

		$classification = $this->sql()->tableClassification()->whereClasses_id($classes_id);
		$classification = $this->classification_finde_active_list($classification);
		
		$classification->joinPerson()->whereUsers_id("#classification.users_id")->fieldBirthday();

		$classification = $classification->select()->allAssoc();

		$age = 0;
		$sum = 0;
	
		foreach ($classification as $key => $value) {
			if(preg_match("/^(\d{4})(\d{4})$/", $value['birthday'], $y)) {
				$age += intval($this->dateNow("Y")) - intval($y[1]);
				$sum++;
			}
		}

		return ($sum > 0) ? round($age / $sum): "-";


	}
}
?>