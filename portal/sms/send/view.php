<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'ارسال پیامک';
		
		//------------------------------ make chane password form
		$hidden = $this->form("#hidden")->value("send_sms");
		$number = $this->form("text")->name("number")->label("number");
		$text = $this->form("#text_desc")->name("text")->label("text");
		$submit = $this->form("submit")->value("send");
		
		$send_sms = array();
		
		$send_sms[] = $hidden->compile();
		$send_sms[] = $number->compile();
		$send_sms[] = $text->compile();
		$send_sms[] = $submit->compile();
		
		$this->data->send_sms = $send_sms;
	}
}
?>