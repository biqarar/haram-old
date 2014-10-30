<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */

class view extends main_view{

	public function config(){
		//------------------------------ global
		$this->global->page_title = "classes";


		//------------------------------ classes list
		$classes_detail = $this->sql(".list", "classes", function($query) {
			$query->limit(80);
		})

		->addCol("detail", "classes")
		->select(-1, "detail")
		->html($this->detailLink("classes"))

		->addCol("classification","class")
		->select(-1, "classification")
		->html($this->link("classification/classesid=%id%"))

		->compile();

		//------------------------------ convert paln_id , teacher , place id , ... to name of this
		$this->detailClasses($classes_detail);
		
		// ->addColEnd("edit", "edit")
		// ->select(-1, "edit")
		// ->html($edit);

		$this->data->list = $classes_detail;
	}
}
?>