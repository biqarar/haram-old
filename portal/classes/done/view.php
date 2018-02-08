 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "ثبت اتمام کلاس";

		//---------------------- check branch
		$this->sql(".branch.classes", $this->xuId("classesid"));
		
		//------------------------------ list of classes
		$classes_detail = $this->sql(".list", "classes", function ($query) {
			$query->whereId($this->xuId("classesid"));
		})->compile();

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

		$this->global->classesid = $this->xuId("classesid");

		
	}
}
?>