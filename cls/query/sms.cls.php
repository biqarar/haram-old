<?php
class query_sms_cls extends query_cls
{
	
	public $sms_api;

	public function send(){
		if(!$this->sms_api) $this->sms_api = new sms_lib();
		return call_user_func_array(array($this->sms_api, 'send'), func_get_args());
	}

	public function drafts($tag = false) {
		$text = $this->sql()->tableDrafts()->whereTag($tag)->limit(1)->select();
		if($text->num() == 1) {
			return $text->assoc("text");
		}
		return $tag;
	}

	public function replase_str($text = false) {
			/**
			* name:tablePerson()->fieldName();
			* statrt_time:tableClasses()->fieldStart_time()
			*/
	}

	public function send_users($users_id = false, $tag = false) {
			$number = $this->sql()->tableBridge()
				->whereUsers_id($users_id)
				->andTitle("mobile")
				->limit(1)->select()->assoc("value");
			$this->send($number, $this->drafts($tag));
	}

	public function send_classes($classes_id = false, $tag = false) {
		$classes_list = $this->sql()->tableClassification()->whereClasses_id($classes_id)->fieldId();

		$classes_list = $this->classification_finde_active_list($classes_list);
		
		$classes_list->joinBridge()->whereUsers_id("#classification.users_id")->andTitle("mobile")->fieldValue();

		$list = $classes_list->select()->allAssoc();
		
		$sms = $this->drafts($tag);

		foreach ($list as $key => $value) {
			$this->send($value['value'], $sms);
		}

	}

}
?>