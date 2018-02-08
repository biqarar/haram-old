<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = 'نتایج مسابقه';

		$header = array(
			// "شماره",
			// "مسابقات تیمی",
			"تیم",
			"تعداد مسابقه",
			"تعداد برد ",
			"تعداد باخت",
			"تعداد مساوی",
			"میانگین درصد",
			"امتیاز",
			"بیشتر"
			);

		$list = $this->sql("#result", $this->xuId());
		$this->data->result = array();

		foreach ($list as $key => $value) {

			$this->data->result[$key]['name'] = $value['groupname'];
			$this->data->result[$key] =array("groupname" => $value['groupname'],"result" => array("header" => $header, "list" => $value['result']));

		}
		// var_dump($this->data->result);exit();

	}

}
?>