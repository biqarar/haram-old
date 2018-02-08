<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class model extends main_model{

	public function post_insertapi() {


		$classificationid = $this->xuId("classificationid");

		//--------------------- check branch
		$this->sql(".branch.classification", $classificationid);

		// $mark = $this->sql()->tableClassification()->whereId($classificationid)->limit(1)->select();
		$duplicate = $this->sql()->tableCertification()->whereClassification_id($classificationid)
			->limit(1)->select()->num();
		if($duplicate == 0 ) {

			$users_id = $this->sql()->tableClassification()->whereId($classificationid)->limit(1)->select()->assoc("users_id");
			$price = $this->sql(".price.sum_price", $users_id);

			if($price['sum_active'] < 0 && !global_cls::supercertification()) {
				debug_lib::fatal("موجودی فعال شهریه فراگیر منفی است و امکان ثبت درخواست گواهی نامه وجود ندارد");
			}

			if(!$this->sql(".pasportCheck", $users_id) && !global_cls::supervisor()){
				debug_lib::fatal("تاریخ اعتبار گذر نامه ایشان به اتمام رسیده است");
			}

			$x = $this->sql()->tableCertification()
			->setClassification_id($classificationid)
			->setDate_request($this->dateNow())
			->insert();

			$this->commit(function(){
				debug_lib::true("گواهی نامه با موفقیت ثبت شد");
			});

		}elseif($duplicate != 0 ){

			debug_lib::fatal("این گواهی نامه قبلا ثبت شده است");
		
		}else{
		
			debug_lib::fatal("ثبت گواهی نامه با خطا مواجه شده است");
		
		}
	}
}
?>