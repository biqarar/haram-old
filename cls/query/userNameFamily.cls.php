<?php
class query_userNameFamily_cls extends query_cls
{
	public function config($usersid = false) {
		
		$return =  $this->query($usersid);
		
		return $return['name'] . " " . $return['family'];
	}

	public function name($usersid= false){
		$return = $this->query($usersid);
		return $return['name'];
	}


	public function family($usersid= false){
		$return = $this->query($usersid);
		return $return['family'];
	}

	
	public function query($usersid = false) {
		return  $this
					->sql()
					->tablePerson()
					->whereUsers_id($usersid)
					->limit(1)
					->fieldName()
					->fieldFamily()
					->select()
					->assoc();
	}


}
?>