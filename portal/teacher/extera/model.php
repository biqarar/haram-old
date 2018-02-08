<?php 
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */

class model extends main_model{

	public function sql_person_extera_id($usersid = false) {
		return $this->sql()->tablePerson_extera()->whereUsers_id($usersid)->limit(1)->select()->assoc("id");
	}

	public function makeQuery() {
		return  $this->sql()->tablePerson_extera()
  					->setUsers_id($this->SESSION_usersid())
  					->setPlace_birth("'" . post::place_birth() . "'")
  					->setChild_daughter(post::child_daughter())
  					->setChild_son(post::child_son())
  					->setDependents(post::dependents())
  					->setSoldiering(post::soldiering())
  					->setExemption_type(post::exemption_type())
  					->setJob(post::job())
  					->setResidence(post::residence())
  					->setHealth(post::health())
  					->setTreated(post::treated())
  					->setStature(post::stature())
  					->setWeight(post::weight())
  					->setBlood_group(post::blood_group())
  					->setDisease(post::disease())
  					->setInsurance_type(post::insurance_type())
  					->setInsurance_code(post::insurance_code())
  					->setGood_remember(post::good_remember())
  					->setBad_remember(post::bad_remember())
  					->setTahqiq(post::tahqiq())
  					->setTartil(post::tartil())
  					->setTajvid(post::tajvid())
  					->setMelli_account(post::melli_account())
  					->setMelat_account(post::melat_account());
	}
	
	public function post_add_person_extera() {
		//----------------------------- got to users/option/model to add users

		$person_extera = $this->makeQuery()->insert();
		// print_r($person_extera->string());

		// die();
		$this->commit(function(){
			debug_lib::true("[[insert person_extera successful]]");
		});

		$this->rollback(function(){
			debug_lib::fatal("[[insert person_extera failed]]");
		});
		// var_dump($person_extera);

	}

	public function post_edit_person_extera() {
		
		//----------------------------- update query
		$sql = $this->makeQuery()->whereId($this->sql_person_extera_id($this->SESSION_usersid()))->update();
		// print_r($sql->string());
	
		//----------------------------- commit code
		$this->commit(function() {
			debug_lib::true("[[update person_extera successful]]");
		});
		
		//----------------------------- rool back code
		$this->rollback(function() {
			debug_lib::fatal("[[update person_extera failed]]");
		});
	}
}
?>