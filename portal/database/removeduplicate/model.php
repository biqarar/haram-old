<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public $main_users_id = false;
	public $duplicate_users_id = array();
	public $users_id = array();
	public $nationalcode = false;
	public $show = array();

	public function post_merge(){
		set_time_limit(30000);
		ini_set('memory_limit', '-1');
		ini_set("max_execution_time", "-1");

		$username1 = post::username1();
		$username2 = post::username2();

		// if(intval($username1) > intval($username2)){
		// 	$temp = $username2;
		// 	$username2 = $username1;
		// 	$username1 = $temp;
		// }
		
		if($username1 == $username2) debug_lib::fatal("نام کاربری باهم برابر است");
		if($username1 == "" || $username2 == "") debug_lib::fatal("هر دو نام کاربری را وارد کنید");


		$user_data1 = $this->sql()->tableUsers()->whereUsername($username1)->limit(1)->select();
		$user_data1 = ($user_data1->num() == 1) ? $user_data1 = $user_data1->assoc() : false;

		$user_data2 = $this->sql()->tableUsers()->whereUsername($username2)->limit(1)->select();
		$user_data2 = ($user_data2->num() == 1) ? $user_data2 = $user_data2->assoc() : false;

		if(!$user_data1 OR !$user_data2) debug_lib::fatal("نام کاربری اشتباه است");

		//------------- person
		$this->person($user_data1['id'], $user_data2['id']);

		//------------- bridge
		$this->bridge($user_data1['id'], $user_data2['id']);

		//------------- classification
		$this->classification($user_data1['id'], $user_data2['id']);

		//------------- price
		$this->price($user_data1['id'], $user_data2['id']);

		//------------- users_branch
		$this->users_branch($user_data1['id'], $user_data2['id']);

		//------------- users_description
		$this->users_description($user_data1['id'], $user_data2['id']);

		//------------- file_users
		$this->file_users($user_data1['id'], $user_data2['id']);

		$this->commit(function(){
			debug_lib::true("تلفیق انجام شد");
		});
		$this->rollback(function(){
			debug_lib::fatal("خطا در تلفیق");
		});


	}

	public function person($users_id1 = false, $users_id2 = false) {

		$person1 = $this->sql()->tablePerson()->whereUsers_id($users_id1)->limit(1)->select()->assoc();
		$person2 = $this->sql()->tablePerson()->whereUsers_id($users_id2)->limit(1)->select()->assoc();
		if(!$person1 OR !$person2) return false;
		
		$update = $this->sql()->tablePerson()->whereId($person2['id']);
		$is_update = false;
		foreach ($person1 as $key => $value) {
			
			if($key == "id") continue;
			if($key == "users_id") continue;
			if($person2[$key] != "" ) continue;
			if($person1[$key] == "" AND $person2[$key] != "") continue;

			$field = "set" . ucfirst($key);
			
			if($person1[$key] != "" AND $person2[$key] == ""){
				$update->$field($person1[$key]);
				$is_update = true;
			}	
		}
		if($is_update) {
			$update = $update->update();
		}
		$delete = $this->sql()->tablePerson()->whereId($person1['id'])->delete()->result();

	}

	public function bridge($users_id1 = false, $users_id2 = false) {
		$bridge1 = $this->sql()->tableBridge()->whereUsers_id($users_id1)->select()->allAssoc();
		$bridge2 = $this->sql()->tableBridge()->whereUsers_id($users_id2)->select()->allAssoc();
		
		foreach ($bridge1 as $key1 => $value1) {

			$check = $this->sql()->tableBridge()
				->whereUsers_id($users_id2)
				->andTitle($value1['title'])
				->andValue($value1['value'])->select();
				
			if($check->num() == 0) {
				#update
				$update = $this->sql()->tableBridge()
					->whereId($value1['id'])
					->setUsers_id($users_id2)
					->update();
					
			}else{
				#duplicate must be remove
				$delete = $this->sql()->tableBridge()->whereId($check->assoc("id"))->delete();
			}
		}

	}

	public function classification($users_id1 = false, $users_id2 = false) {
		$update = $this->sql()
			->tableClassification()
			->whereUsers_id($users_id1)
			->setUsers_id($users_id2)
			->update();


	}

	public function price($users_id1 = false, $users_id2 = false) {
		$update = $this->sql()
			->tablePrice()
			->whereUsers_id($users_id1)
			->setUsers_id($users_id2)
			->update();

	}

	public function users_branch($users_id1 = false, $users_id2 = false) {
		$list_branch = $this->sql()->tableUsers_branch()->whereUsers_id($users_id1)->select()->allAssoc();
		foreach ($list_branch as $key => $value) {
			$check = $this->sql()->tableUsers_branch()->whereUsers_id($users_id2)
					->andBranch_id($value['branch_id'])->select();
			if($check->num() == 0){
				$insert = $this->sql()->tableUsers_branch()
					->setUsers_id($users_id2)
					->setBranch_id($value['branch_id'])
					->setType($value['type'])
					->setStatus($value['status'])
					->insert();
			}
		}
		$update = $this->sql()->tableUsers_branch()->whereUsers_id($users_id1)->setStatus("delete")->update();

	}

	public function users_description($users_id1 = false, $users_id2 = false) {
		$update = $this->sql()->tableUsers_description()
			->whereUsers_id($users_id1)->setUsers_id($users_id2)->update();
	}

	public function file_users($users_id1 = false, $users_id2 = false) {
		$update = $this->sql()->tableFile_user()
			->whereUsers_id($users_id1)->setUsers_id($users_id2)->update();
	}




	public function _get_delete(){
		die();
		$table = get::table();
		$status = get::status();
		$id = get::id();
		if($status == "delete"){
			$x = "table" .ucfirst($table);
			$query = $this->sql()->$x()->whereId($id)->delete();
			// echo "result: ". $query->result();
			// echo "<br> error : " . $query->error();
			// echo "<br> query: " . $query->string();
			$this->commit(function(){

				var_dump(debug_lib::complie());exit();
				debug_lib::true("ok");
			});
			$this->rollback(function(){


				var_dump(debug_lib::complie());exit();
				debug_lib::fatal("fuck");
			});
			
		}
		// var_dump($_GET);exit() 	;
	}

	public function sql_duplicate($nationalcode = false ){
		var_dump(":|");exit();
		$this->nationalcode = $nationalcode;
		//----------- if get the url delete it
		$this->_get_delete();
		
		//------------------ fund person whit this nationalcode
		$dbl_person = $this->sql()->tablePerson()->whereNationalcode($nationalcode)->select();

		//------------------ if duplicate is true
		if ($dbl_person->num() > 1) {
			$dbl_person = $dbl_person->allAssoc();
			//-------------- show data
			$this->table($dbl_person, "person");
			//-------------- list of table muse be change
			$show_table = array("bridge","price","classification", "users_branch");
			$ret = array();
			foreach ($dbl_person as $key => $value) {
				//---------------- save users_id
				$this->users_id[] = $value['users_id'];

				if(!$this->main_users_id){
					$this->main_users_id = $value['users_id'];
				}else{
					$this->duplicate_users_id[] = $value['users_id'];
				}

				$x = $this->sql()->tableUsers()->whereId($value['users_id'])->limit(1)->select()->assoc();
				array_push($ret, $x);
			}

			$this->table($ret, "users");

			foreach ($show_table as $key => $value) {
				$x = "table" . ucfirst($value);
				$sql = $this->sql()->$x();
				foreach ($dbl_person as $k => $v) {
					if($k == 0){
						$sql->whereUsers_id($v['users_id']);
					}else{
						$sql->orUsers_id($v['users_id']);
					}
					
				}
			$sql = $sql->select()->allAssoc();
			$this->table($sql, $value);
		}

			


		}
		return $this->show;

	
	}

	public function table($array, $title){
		// return ;
		// var_dump($array);
		$href ="database/status=removeduplicate/nationalcode=" . $this->nationalcode;
		$echo = "<h3>$title</h3>";
		$echo .= "<table border=1>";
		foreach ($array as $key => $value) {
			$echo .= "<tr>";
			if($key == 0){
					$echo .= "<th>";
					$echo .= "num";
					$echo .= "</th>";
				
				foreach ($value as $k => $v) {
					$echo .= "<th>";
					$echo .= $k;
					$echo .= "</th>";
				}
				
					$echo .= "<tr>";
					$echo .= "<th>";
					$echo .= $key;
					$echo .= "</th>";

				foreach ($value as $k => $v) {
					if($k == 0) {
						$v = "<a href='$href?table=$title&status=delete&id=$v'>$v</a>";
					}
					$echo .= "<td>";
					$echo .= $v;
					$echo .= "</td>";
				}
				$echo .= "</tr>";
			}else{
				$echo .= "<th>";
					$echo .= $key;
					$echo .= "</th>";
				foreach ($value as $k => $v) {
					if($k == 0) {
						$v = "<a href='$href?table=$title&status=delete&id=$v'>$v</a>";
					}
					$echo .= "<td>";
					$echo .= $v;
					$echo .= "</td>";
				}

			}
			$echo .= "</tr>";
		}
		$echo .= "</table>";
		$this->show[] = $echo;
	}
}
?>