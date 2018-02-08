<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config() {

		//------------------------------ global
		$this->global->page_title = "absence";

		$this->classeTopLinks();


		//------------------------------ absence tag
		$absence_tag = $this->tag("input")->type("text")->date("date")->addClass("absence-date");

		//------------------------------ get detail classes
		if(config_lib::$surl['classesid']){

			//-------------- ceck branch
			$this->sql(".branch.classes", config_lib::$surl['classesid']);
			
			//------------------------------ classes id
			$classes_id = isset(config_lib::$surl['classesid']) ? config_lib::$surl['classesid'] : 0;
			$this->global->classesid = $classes_id;

			$classes_detail = $this->sql(".list" , "classes", function ($query) {
				$query->whereId(config_lib::$surl['classesid']);
			})->removeCol("type,name,start_time,end_time")

			//------------------------------ print link
			->addCol("print", "print")
			->select(-1, "print")
			->html($this->link("classification/printlist/classesid=%id%", "href" , "icoletters a-undefault"));

			//------------------------------ main date absence tag
			$main_absence_tag = $absence_tag->removeClass("absence-date")->addClass("absence-date-main");
			
			//------------------------------ main date absence col
			$classes_detail = $classes_detail->addCol("date", "date")->select(-1 , "date")
			->html($main_absence_tag);

			$classes_detail = $classes_detail->compile();
			
			//------------------------------ change users id to name and family to show
			$classes_detail = $this->detailClasses($classes_detail);
			// 
			// var_dump($classes_detail);exit();
			$this->data->list = $classes_detail;
		}
		$this->data->msg = false;
		
		if($this->data->list['list'][0]['status'] == "done") {
			$this->data->msg = "این کلاس به اتمام رسیده است.";
		}else{
			
			$this->data->dataTable = $this->dtable("absence/status=classeslist/classesid=" . $this->xuId("classesid").'/',
				array("name", "family", "date_entry", "date_delete", "because","type" ,"ثبت غیبت" , "ثبت غیبت بیشتر" , "نمایش غیبت ها"));
		}
	}
}
?>