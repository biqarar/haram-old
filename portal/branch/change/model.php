<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	public function post_apichange() {
		$type = $this->xuId("type");
		$usersbranchid = $this->xuId("usersbranchid");

		$this->sql(".branch.usersbranch", $usersbranchid);

		if($type == "student" || $type == "teacher" || $type == "operator"){
			$this->sql()->tableUsers_branch()->whereId($usersbranchid)->setType($type)->update();
		}

		if($type == "waiting" || $type == "enable" || $type == "block" || $type == "delete"){		
			$this->sql()->tableUsers_branch()->whereId($usersbranchid)->setStatus($type)->update();
		}

		$this->commit(function(){
			debug_lib::true("تغییر نوع کاربر انجام شد");
		});
		$this->rollback(function(){
			debug_lib::fatal("اشکال در تغییر نوع و وضعیت فراگیر");
		});
	}

	public function post_branch_change(){
		
		if(post::branch_id()== "" ){
			debug_lib::fatal("لطفا شعبه و نوع کاربر را انتخاب کنید");
		}
		
		$sql = $this->sql()
				->tableUsers_branch()
				->setUsers_id($this->xuId("usersid"))
				// ->setType(post::type())
				->setBranch_id(post::branch_id())
				->insert();
		$this->commit(function(){
			debug_lib::true("شعبه جدید برای کاربر اضافه شد");
		});
		$this->rollback(function(){
			debug_lib::fatal("خطا در ثبت شعبه جدید برای کاربر");
		});
	}

	public function post_apichangeusersbranch(){

		$branch_id = $this->xuId("branchid");
		$users_id = $this->xuId("usersid");


		$sql = $this->sql()
				->tableUsers_branch()
				->setUsers_id($users_id)
				->setBranch_id($branch_id)
				->insert();

		$this->commit(function(){
			debug_lib::true("پرونده فراگیر در این شعبه فعال شد");
		});
		$this->rollback(function(){
			debug_lib::fatal("خطا در ثبت شعبه جدید برای کاربر");
		}); 

	}
}
?>