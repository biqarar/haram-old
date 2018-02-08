<?php
class query_pasportCheck_cls extends query_cls {

	public function config($usersid) {

		$person = $this->sql()->tablePerson()->limit(1)->whereUsers_id($usersid)
					->fieldPasport_date()
					->fieldNationality()
					->select()->assoc();	

		$dateNow = $this->dateNow("Ymd");

		if($person['nationality'] != 97  && 
		   $person['nationality'] != '' && 
		   $dateNow > $person['pasport_date'] && 
		   !global_cls::superperson()) 
		{
			return false;
		}
		return true;
	}
}
?>