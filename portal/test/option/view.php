<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{
	public function config(){
		$person = $this->sql(".list.card", "person" , $this->xuId()); 
		$this->data->list = $person;
		// var_dump($person);
		// exit();
	}
}
?>