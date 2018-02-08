<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view
{
	/**
	 * { function_description }
	 */
	public function config()
	{
		//------------------------------  global
		$this->global->page_title = "وضعیت شهریه های کلاس";

		$this->classesDetail();

		//------------------ check branch
		$this->sql(".branch.classes",$this->xuId("classesid"));

		$this->data->classesid = $this->xuId("classesid");

		$this->data->dataTable = $this->dtable("price/status=classeslist/classesid=" . $this->xuId("classesid").'/',
			array("username", "name", "family", "date_entry", "date_delete", "because",  "موجودی فعال" , "more"));

		if(global_cls::superprice("rule"))
		{
			$this->data->price_permission = true;

			$f = $this->form('@price', $this->urlStatus());
			$f->type->child(1)->checked("checked");
			$f->remove("status");
			$f->title->child(0)->selected("selected");
			$f->remove('type,plan_id,pay_type,card,transactions');

		}

		// $this->data->classes_id = $this->data->list['list'][0]['id'];
	}
}
?>