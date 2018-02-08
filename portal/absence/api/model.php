<?php 
/**
* 
*/
class model extends main_model {

	public $type = false;
	
	function post_api()	{

		$classification = $this->xuId("classification");
		$type = $this->xuId("type");
		$date = $this->xuId("date");
		
		$result = $this->sql(".absence.insert", $classification, $type, $date);	
	}

	public function post_delete(){
		$classification = $this->xuId("classification");
		$date = $this->xuId("date");

		$check = $this->sql()->tableAbsence()->whereClassification_id($classification)->andDate($date);

		if($check->select()->num() == 0) {
			debug_lib::fatal("هیچ گونه غیبت برای این فراگیر در این تاریخ ثبت نشده است");
		}

		$this->type = $check->select()->assoc("type");

		$x = $check->delete();
		// $this->sql(".absence.autoremove",$classification);
		
		$this->commit(function(){
			debug_lib::true(_($this->type) .  " حذف شد");
		});

		$this->rollback(function(){
			debug_lib::fatal("خطا در ثبت اطلاعات");
		});
	}
}

?>