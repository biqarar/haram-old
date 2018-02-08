<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {

	public function post_send_sms(){
		$this->sms(post::number(), post::text());
		debug_lib::true("پیامک ارسال شد");
	}
}
?>