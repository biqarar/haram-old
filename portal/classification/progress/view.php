<?php 
/**
 * 
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = _("وضعیت نمرات کلاس");

		//------------------------------  set users_id
		$users_id  = $this->xuId();

			//----------------------- check banch

		$classesid = $this->xuId("id");
		$this->sql(".branch.classes",$classesid);

		$classes_detail = $this->sql(".classesDetail", $classesid);
		$this->data->classes_detail = $classes_detail['page_title'];
		
		//------------------------------ get detail classes
		// $this->classesDetail();
		$chart = $this->sql("#progress", $classesid);
		$this->data->chart = $chart;
	}
} 
?>