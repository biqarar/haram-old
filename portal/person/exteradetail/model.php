<?php 
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */

class model extends main_model{

	public function makeQuery() {
		var_dump("useless");exit();
		return  $this->sql()->tablePerson_extera()
  					->setUsers_id($this->xuId("usersid"))
  					->setPlace_birth(post::place_birth())
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
  					->setTajvid(post::tajvid());
	}
	
	public function post_add_person_extera() {
		//----------------------------- got to users/option/model to add users
		$person_extera = $this->makeQuery()->insert();

		$this->commit(function(){
			debug_lib::true("ok");
		});

		$this->rollback(function(){
			debug_lib::fatal("shet");
		});
		// var_dump($person_extera);

	}

	public function post_edit_person() {

		//----------------------------- make object sql to update person
	
				
				
		// The line for this is that if this field was filled can not be empty and must be changed
		//----------------------------- if country != irna and is set post pasport date update this
		if(post::pasport_date() != "") {
			$makeQuery->setPasport_date(post::pasport_date());
		}

		//----------------------------- if country != irna and is set post pasport date update this
		if(post::nationalcode() != "") {
			$makeQuery->setNationalcode(post::nationalcode());
		}

		//----------------------------- if nationality != null update this
		if(post::nationality() != "") {
			$makeQuery->setNationality(post::nationality());
		}
		
		//----------------------------- if code != null update this
		if(post::code() != "") {
			$makeQuery->setCode(post::code());
		}

		
		//----------------------------- if child != null update this
		if(post::child() != "") {
			$makeQuery->setChild(post::child());
		}

		//----------------------------- if britday != null update this
		if(post::birthday() != "") {
			$makeQuery->setBirthday(post::birthday());
		}

		//----------------------------- if from != null update this (foreign key to city table)
		if(post::from() != "") {
			$makeQuery->setFrom(post::from());
		}

		//----------------------------- if education != null update this (foreign key to education table)
		if(post::education_id() != "") {
			$makeQuery->setEducation_id(post::education_id());
		}

		//----------------------------- update query
		$sql = $makeQuery->whereId($this->xuId())->update();
	
		//----------------------------- commit code
		$this->commit(function() {
			debug_lib::true("[[update person successful]]");
		});
		
		//----------------------------- rool back code
		$this->rollback(function() {
			debug_lib::fatal("[[update person failed]]");
		});
	}
}
?>