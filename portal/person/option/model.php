<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */

class model extends main_model{

	public function sql_load_file($person_id = false) {
		$users_id = $this->sql()->tablePerson()->whereId($person_id)->fieldUsers_id()->limit(1)->select()->assoc("users_id");
		return $this->load_file($users_id);
	}


	public function load_file($users_id = false) {

		$file_user = $this->sql()->tableFile_user()->whereUsers_id($users_id)->select();

		$directory = "portal/updfile/";

		$path = array();

		if($file_user->num() > 0 ) {
			foreach ($file_user->allAssoc() as $key => $value) {
				$file = $this->sql()->tableFiles()->whereId($value['file_id'])->limit(1)->select()->assoc();
				$path[] = $directory . $file['folder'] . '/' . $file['title'] . "." . $file['type'];
			}
		}
		return $path;

	}

	public function sql_find_from_name($id = false){
		$ret = '';
		if($id != ""){
			$sql = $this->sql()->tableCity()->whereId($id);
			$sql->joinProvince()->whereId("#city.province_id")->fieldName("pname");
			$r = $sql->limit(1)->select()->assoc();
				$ret = $r['pname'].' - '.$r['name'];
		}
		return $ret;
	}

	public function makeQuery() {

		//------------------------------ person sql object
		$sqlPerson = $this->sql()->tablePerson()
		->setName(post::name())
		->setFamily(post::family())
		->setFather(post::father())
		->setBirthday(post::birthday())
		->setGender(post::gender())
		->setNationality(post::nationality())
		->setNationalcode(post::nationalcode())
		->setCode(post::code())
		->setMarriage(post::marriage())
		->setFrom(post::from())
		->setChild(post::child())
		->setEducation_id(post::education_id())
		->setEducation_howzah_id(post::education_howzah_id())

		->setPasport_date(post::pasport_date());

		// $from = post::from();
		// if(preg_match("/^\d$/", $from)){
		// 	$sqlPerson->setFrom(post::from());
		// }

		return $sqlPerson;
	}


	public function post_add_person() {

		$key = true;


		//------------------------------ check captcha if loaded
		if(!$this->sql(".loginCounter.register")){
			$key = false;
		}

		if(isset($_SESSION['CAPTCHA_GNA']) && $_SESSION['CAPTCHA_GNA']) {
			if(post::captcha() == $_SESSION['CAPTCHA_GNA']){
				$key = true;
				$this->sql(".loginCounter.clear");
			}
		}




		//------------------------------ check duplicate record
		if(post::branch_id() == "" ){
			$key = false;
			debug_lib::fatal("لطفا شعبه انتخاب کنید");
		}
		//------------------------------ if nationality is iran check nationalcode
		// if(post::nationality() == 97) {

			$duplicate_person = $this->sql()
				->tablePerson()
				->whereNationalcode(post::nationalcode())
				->select();

			if($duplicate_person->num() >= 1) {
				$key = false;
				debug_lib::fatal($this->duplicate_msg($duplicate_person->assoc("users_id")));

			}
		// }


		//------------------------------ check for empty text for name,family,father
		if(post::name() == '' || post::family() == '' || post::father() == ''){
			$key = false;
			debug_lib::fatal("نام، نام خانوادگی و نام پدر وارد نشده است");
		}


		//------------------------------ if no error in field
		if ($key){

			//------------------------------ check register counter
			//------------------------------ disable for portal
			//------------------------------ enable for real web site

			 // if(!$this->sql(".loginCounter.register") && (post::captcha() != $_SESSION['CAPTCHA_GNA'])){
				// 	debug_lib::fatal("[[insert users failed, 60 register in 600 Seconds!]]");
			 // }else{
			 // 		$this->sql(".loginCounter.clear");
			 // }

			//------------------------------ get new username
			$username = $this->sql(".username.set");


			//------------------------------ insert into users
			//------------------------------ get users_id to set into person table
			$q_users_id  = $this->sql()->tableUsers()
						->setEmail(post::email())
						->setPassword(md5(post::nationalcode()))
						->setUsername($username)
						->insert();
			$users_id = $q_users_id->LAST_INSERT_ID();



			//------------------------------ insert into person table
			$person_id = $this->makeQuery()->setUsers_id($users_id)->insert()->LAST_INSERT_ID();


			//------------------------------ insert into bridge table, phone and mobile
			if(post::mobile() !== "")
				$this->sql()->tableBridge()
				->setUsers_id($users_id)
				->setTitle("mobile")
				->setValue(post::mobile())
				->insert();

			if(post::mobile2() !== "")
				$this->sql()->tableBridge()
				->setUsers_id($users_id)
				->setTitle("mobile")
				->setValue(post::mobile2())
				->insert();

			if(post::phone()  !== "")
				$this->sql()->tableBridge()
				->setUsers_id($users_id)
				->setTitle("phone")
				->setValue(post::phone())
				->insert();

			//------------------------------ set users_branch if other sql is ok
			$sqlBranch = $this->sql()->tableUsers_branch()
						->setUsers_id($users_id)
						->setBranch_id($this->post_branch())
						->insert();



			//------------------------------ commit code
			$this->commit(function($username = false , $users_id = false, $person_id = false) {
				debug_lib::true("ثبت نام با موفقیت انجام شد <br>
								 نام کاربری شما <b style='color: #0C706F'>  $username  </b> <br>
								 و کلمه عبور شما کد ملی یا شماره گذر نامه شما می باشد.
								 ".
								 "<br><br>شهریه" .
								 $this->tag("a")
								 ->href("price/status=add/usersid=$users_id")
								 ->class("icoprice")
								 ->title("ثبت شهریه برای این فراگیر")
								 ->render() . "<br> پل های ارتباطی" .
								 $this->tag("a")
								 ->href("bridge/status=add/usersid=$users_id")
								 ->class("icoshare")
								 ->title("ثبت پل های ارتباطی برای فراگیر")
								 ->render()
								   . "<br> ویرایش اطلاعات" .
								 $this->tag("a")
								 ->href("person/status=edit/id=$person_id")
								 ->class("icoedit")
								 ->title("ویرایش اطلاعات فراگیر")
								 ->render()
								 );
			}, $username, $users_id, $person_id);
		}

		$this->rollback(function() {
			debug_lib::fatal("[[insert users failed]]");
		});
	}

	public function post_edit_person() {

		//------------------ check branch
		$this->sql(".branch.person",$this->xuId());


		//----------------------------- update query
		$sql = $this->makeQuery()->whereId($this->xuId())->update();


		//----------------------------- commit code
		$this->commit(function() {
			debug_lib::true("[[update person successful]]");
		});

		//----------------------------- rool back code
		$this->rollback(function() {
			debug_lib::fatal("[[update person failed]]");
		});
	}

	public function duplicate_msg($users_id = false) {
		$branch = $this->sql()->tableUsers_branch()->whereUsers_id($users_id);
		$branch->joinBranch()->whereId("#users_branch.branch_id")->fieldName("branch");
		$branch = $branch->select();



		$username = $this->sql()->tableUsers()->whereId($users_id)->limit(1)->select()->assoc("username");

		$person = $this->sql()->tablePerson()->whereUsers_id($users_id)->select()->assoc();

		$branch_id = $this->post_branch();


		$msg = "\nکد ملی تکراری است \n ";
		$msg .= "\n ----------------------- \n ";
		$msg .= " پرونده این فراگیر قبلا در ";

		if($branch->num()== 1) {
			$x = $branch->assoc();

			$msg .= " شعبه \n";
			$msg .= "<b style='color: #0C706F'> " . $x['branch'] . "</b> ( " . _($x['type']) . " )\n";

		}elseif($branch->num() > 1) {
			$msg .= " شعبه های \n";
			foreach ($branch->allAssoc() as $key => $value) {
				$msg .= "<b style='color: #0C706F'> " .$value['branch']. "</b> ( " . _($value['type']) . " )\n";
			}

		}

		$msg .= " با شماره پرونده: <b><a  style='color: #0C706F' title='نمایش پرونده' href='users/learn/id=$users_id'>" . $username . "</a></b>";
		$msg .= "\n با مشخصات";
		$msg .= "\n نام: <b style='color:#0C706F'>"  . $person['name'] . "</b>";
		$msg .= "\n نام خانوادگی: <b style='color: #0C706F'>" . $person['family'] . "</b>";
		$msg .= "\n نام پدر: <b style='color: #0C706F'>" . $person['father'] . "</b>";

		$msg .= "\n ثبت شده است. ";
		$msg .= "\n ----------------------- \n ";
		// print_r($_SESSION);exit();
		$permission = (isset($_SESSION['my_user']['permission']['tables']['users_branch']['update'])) ? true : false;
		if($permission || global_cls::supervisor()){

		$msg .= "<a style='text-decoration: none; color:white; cursor: pointer;'><div style='width: 70px;background: #0C706F;border-radius: 7px;padding: 25px 50px 25px 50px !important;text-align: center;display: inline-block;' onclick='apichangeusersbranch($branch_id,$users_id)'>انتقال پرونده به این شعبه</div></a><br>";

		}else{
		$msg .= "\n لطفا جهت فعال سازی پرونده ایشان در این شعبه \n نام کاربری فراگیر را یادداشت کرده
		و به مسئول شعبه خود اطلاع دهید. \n ";

		}

		return nl2br($msg);
	}
}
?>