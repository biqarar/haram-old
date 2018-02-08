<?php 

/**
* @auther reza mohiti
*/
class model extends main_model {
	public function post_apichange() {

		$type = $this->xuId('type');
		
		$certificationid = $this->xuId("certificationid");
		
		//----------------------- check branch
		$this->sql(".branch.certification", $certificationid);
		
		if($type == "setdateprint"){
		
			$this->sql()->tableCertification()->setDate_print($this->dateNow())->whereId($certificationid)->update();
			debug_lib::true("تاریخ چاپ ثبت شد");
		
		}elseif($type == "setdatedeliver"){
		
			$this->sql()->tableCertification()->setDate_deliver($this->dateNow())->whereId($certificationid)->update();
			debug_lib::true("تاریخ تحویل ثبت شد");
		
		}elseif($type == "deletedatedeliver"){
		
			$this->sql()->tableCertification()->setDate_deliver("#null")->whereId($certificationid)->update();
			debug_lib::true("تاریخ تحویل حذف شد");
		
		}elseif($type == "deletedateprint"){
		
			$this->sql()->tableCertification()->setDate_print("#null")->whereId($certificationid)->update();
			debug_lib::true("تاریخ تحویل حذف شد");
		}
	}	
}
?>