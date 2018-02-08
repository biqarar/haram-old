<?php 
/**
 * 
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = _("وضعیت نمرات گروه علمی");

		//------------------------------  set users_id
		$users_id  = $this->xuId();

			//----------------------- check banch

		$group = $this->xuId("id");
		$this->sql(".branch.group",$group);

		$this->data->plan_detail = $this->sql(".assoc.foreign" , "group", $group , "name" , "id");
			
		//------------------------------ get detail classes
		// $this->classesDetail();
		$chart = $this->sql("#progress", $group);
		$this->data->chart = $chart;

		// $this->classesDetail();
		$count_class = $this->sql("#classification", $group);
		$this->data->count_class = $count_class;
	}
} 
?>