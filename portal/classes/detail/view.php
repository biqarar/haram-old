 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "classes_detail";

		$this->classeTopLinks();
		

		$edit_link = $this->tag("a")
		->href("classes/status=edit/id=%id%")
		->addClass("icoedit")
		->style("margin : 0px !important;")
		->render();


		//---------------------- check branch
		$this->sql(".branch.classes", $this->xuId());
		
		//------------------------------ list of classes
		$classes_detail = $this->sql(".list", "classes", function ($query) {
			$query->whereId($this->xuId());
		})
		->addColFirst("edit", "edit")
		->select(0, "edit")
		->html($edit_link)
		 ->compile();
		 // var_dump($classes_detail);exit();
		// $classes_detail = $classes_detail->addColEnd("done", "done")
		// ->select(0, "done")
		// ->html($this->tag("a")->href("classes/status=done/classesid=%id%")
		// ->addClass("icoredclose")
		// ->style("margin : 0px !important;")->render())

		//------------------------------ convert paln_id , teacher , place id , ... to name of this
		// $classes_detail = $this->detailClasses($classes_detail);
		foreach ($classes_detail['list'] as $key => $value) {
				$classes_detail['list'][$key]['plan_id']   = $this->sql(".assoc.foreign", "plan", $value["plan_id"], "name");
				$classes_detail['list'][$key]['teacher']   = 
				$this->sql(".assoc.foreign", "person", $value["teacher"], "name", "users_id") . ' ' . 
				$this->sql(".assoc.foreign", "person", $value["teacher"], "family", "users_id");
				$classes_detail ['list'][$key]['place_id']  = $this->sql(".assoc.foreign", "place", $value["place_id"], "name");
			}	
		$this->data->list = $classes_detail;
	}
}
?>