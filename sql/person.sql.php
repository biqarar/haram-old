<?php
namespace sql;
class person {
	public $id           = array('type'=> 'int@10', 'autoI', 'label' => 'person_id');
	public $name         = array('type'=> 'varchar@32', 'label' => 'person_name');
	public $family       = array('type'=> 'varchar@32', 'label' => 'person_family');
	public $father       = array('type'=> 'varchar@16', 'label' => 'person_father');
	public $birthday     = array('type'=> 'int@10', 'label' => 'person_birthday');
	public $gender       = array('type'=> 'enum@male,female', 'label' => 'person_gender');
	public $nationality  = array('type'=> 'int@10', 'label' => 'person_nationality');
	public $from         = array('type'=> 'int@10', 'label' => 'person_from');
	public $nationalcode = array('type'=> 'varchar@16', 'label' => 'person_nationalcode');
	public $code         = array('type'=> 'int@10', 'label' => 'person_code');
	public $marriage     = array('type'=> 'enum@single,married!single', 'label' => 'person_marriage');
	public $child        = array('type'=> 'int@10', 'label' => 'person_child');
	public $casecode     = array('type'=> 'int@10', 'label' => 'person_casecode');
	public $casecode_old = array('type'=> 'int@10', 'label' => 'person_casecode_old');
	public $education_id = array('type'=> 'int@10', 'label' => 'education_academic_id');
	public $education_howzah_id = array('type'=> 'int@10', 'label' => 'education_howzah_id');
	// public $en_name      = array('type'=> 'varchar@32', 'label' => 'person_en_name');
	// public $en_family    = array('type'=> 'varchar@32', 'label' => 'person_en_family');
	// public $en_father    = array('type'=> 'varchar@32', 'label' => 'person_en_father');
	// public $third_name   = array('type'=> 'varchar@32', 'label' => 'person_third_name');
	// public $third_family = array('type'=> 'varchar@32', 'label' => 'person_third_family');
	// public $third_father = array('type'=> 'varchar@32', 'label' => 'person_third_father');
	public $pasport_date = array('type'=> 'int@10', 'label' => 'person_pasport_date');
	public $users_id     = array('type'=> 'int@10', 'label' => 'users_id');

	public $unique       = array("nationalcode");
	public $index        = array("id", "from", "nationality", "education_id", "users_id");

	public $foreign      = array(
		"users_id"           => "users@id!username",
		"from"               => "city@id!name",
		"nationality"        => "country@id!name",
		"education_id"       => "education@id!section",
		"education_howzah_id"       => "education@id!section"
		);

	public function id() {
		$this->validate("id");
	}

	public function name() {
		$this->form("#fatext")->name("name");
		$this->validate()->form->farsi("name should be between 2 and 32 characters");
	}

	public function family() {
		$this->form("#fatext")->name("family");
		$this->validate()->farsi()->form->farsi("family name should be between 2 and 32 characters");
	}

	public function father() {
		$this->form("#fatext")->name("father");
		$this->validate()->farsi()->form->validate("father name should be between 2 and 32 characters");
	}

	public function birthday() {
		$this->form("#date")->name("birthday")->date("date");
		$this->validate()->date()->form->date("birthday incorrect");
	}

	public function gender() {
		$this->form("radio")->name("gender")->label("gender");
		$this->setChild($this->form);
	}

	public function nationality() {
		$this->form("select")->name("nationality");
		$this->setChild();
	}

	public function nationalcode() {
		$this->form("#nationalcode")->required();
	}

	public function code() {
		$this->form("#number")->name("code");
		$this->validate()->number(1,10)->form->number("code incorrect");
	}

	public function from() {
		$this->form("select")->name("from")->addClass("select-city")->data_url("city/api/");
		$this->validate();
	}


	public function marriage() {
		$this->form("radio")->name("marriage");
		$this->setChild($this->form);
		$this->form->child(0)->classname("marriage-form");
		$this->form->child(1)->classname("marriage-form");
	}

	public function child() {
		$this->form("#number")->name("child")->classname("slider-number")->max(10)->min(0);
		$this->validate()->number(1,2)->form->number("number of children is not correct");
	}

	// public function type() {
	// 	$this->form("select")->name("type");
	// 	$this->setChild();
	// }

	public function casecode() {
		$this->form("#number")->name("casecode");
		$this->validate()->number(11);
	}

	public function casecode_old() {
		$this->form("#number")->name("casecode_old");
		$this->validate()->number(7, 9);
	}

	public function education_id() {
		$this->form("select")->name("education_id")->addClass("select-education-section");
		$this->setChild(function($q){
			$q->whereGroup("academic");
		}, function($child, $value){
			$child->label(gettext($value['group']).' '. $value['section'])->value($value['id']); 
		});

	}


	public function education_howzah_id() {
		$this->form("select")->name("education_howzah_id")->addClass("select-education-howzah-section notselect");
		$this->setChild(function($q){
			$q->whereGroup("howzah");
		}, function($child, $value){
			$child->label(gettext($value['group']).' '. $value['section'])->value($value['id']); 
		});

	}

	// public function en_name() {
	// 	$this->form("#entext")->name("en_name");
	// }

	// public function en_family() {
	// 	$this->form("#entext")->name("en_family");
	// }

	// public function en_father() {
	// 	$this->form("#entext")->name("en_father");
	// }

	// public function third_name() {
	// 	$this->form("#fatext")->name("third_name");
	// }

	// public function third_family() {
	// 	$this->form("#fatext")->name("third_family");
	// }

	// public function third_father() {
	// 	$this->form("#fatext")->name("third_father");
	// }

	public function pasport_date() {
		$this->form("text")->name("pasport_date")->date("date");
		$this->validate()->date()->form->date("passport date is not valid");
	}

	public function users_id() {
		// $this->form("hidden")->name("users_id");
		$this->validate("id");
	}
}
?>