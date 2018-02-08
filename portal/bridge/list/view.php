<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */

class view extends main_view{

	public function config(){
		//------------------------------ global
		$this->global->page_title = "bridge";


		$this->data->dataTable = $this->dtable("bridge/status=api/"
			, array(
			"more",
			"username",
			"name",
			"family",
			"title",
			"value",
			"edit"
				));
		// //------------------------------ classes list
		// $classes_detail = $this->sql(".list", "classes", function($query) {
		// 	$query->limit(80);
		// })

		// //------------------------------ detail link
		// ->addCol("detail", "description")
		// ->select(-1, "detail")
		// ->html($this->detailLink("classes"))

		// //------------------------------ edit link
		// ->addCol("edit", "edit")
		// ->select(-1, "edit")
		// ->html($this->editLink("classes"));


		// 	//------------------------------ absence link
		// 	$classes_detail = $classes_detail->addCol("absence","absence")
		// 	->select(-1, "absence")
		// 	->html($this->link("classification/absence/classesid=%id%", "href", "icoattendance"));

		// }else{
		// 	//------------------------------ classification link
		// 	$classes_detail = $classes_detail->addCol("classification","classification")
		// 	->select(-1, "classification")
		// 	->html($this->link("classification/class/classesid=%id%", "href", "icoclasses"));
			
		// 	//------------------------------ print link
		// 	$classes_detail = $classes_detail->addCol("print", "print")
		// 	->select(-1, "print")
		// 	->html($this->link("classification/printlist/classesid=%id%", "href" , "icoletters a-undefault"));
		// }

		// //------------------------------ compile sql.list
		// $classes_detail = $classes_detail->compile();
		
		// //------------------------------ convert paln_id , teacher , place id , ... to name of this
		// $classes_detail = $this->detailClasses($classes_detail);
		

		// $this->data->list = $classes_detail;
	}
}
?>