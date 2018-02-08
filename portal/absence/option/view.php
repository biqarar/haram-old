<?php
/**
* @author Reza Mohiti <rm.biqarar@gmail.com>
*/
class view extends main_view {

	public function config() {

		//------------------------------ globals
		$this->global->page_title = "absence";

		//------------------------------ edit form
		$f = $this->form("@absence", $this->urlStatus());

		//------------------------------ remvoe classification_id
		$f->remove("classification_id");

		//------------------------------ find users id if url == edit
		if($this->urlStatus() == "edit") {
			
			$usersid_classesid = $this->sql("#find_users_id", $this->xuId());
			
			$users_id = $usersid_classesid['usersid'];

			//------------------------------ set url
			$this->global->url = "status=edit/id=" . $this->xuId();

		}else{

			$users_id = $this->xuId("usersid");

			$this->topLinks(array(
			array("title" => "نمایش", "url" => "users/learn/absence/id=$users_id/classesid=0"),
			array("title" => "ثبت", "url" => "absence/status=add/usersid=$users_id"),

			));

			//------------------------------ set url
			$this->global->url = "status=add/usersid=" . $users_id;
		}

		//------------------------------ page title description
		$this->global->users_datail = _($this->urlStatus()) . "   غیبت برای فراگیر " . 
				$this->sql(".assoc.foreign" , "person", $users_id , "name" , "users_id")
				. '  ' . 
				$this->sql(".assoc.foreign" , "person", $users_id , "family" , "users_id");
		
		//------------------------------ edit
		if($this->urlStatus() == "edit") {
			//------------------------------ page title description
			$this->global->users_datail .=  " -  ثبت شده در کلاس "  . $this->sql("#classes_name", $usersid_classesid['classesid']) ; 
			$this->global->page_title = "اصلاح غیبت";
			
			//------------------------------ edit form
			$this->sql(".edit", "absence", $this->xuId(), $f);
		}else{

			//------------------------------ make list of active classes
			$active_classes = $this->sql("#active_classes", $users_id);
			
			$classes = array();
			// $classes[] = $this->form("#hidden")->value("absence_add")->compile();
			// var_dump($active_classes);exit();
			foreach ($active_classes as $key => $value) {
				
				$x = $this->form("checkbox")
						->name("classes_" . $value["classes_id"])
						->label($this->sql("#classes_name", $value['classes_id']))
						->value($value['classification_id']);

				$classes[] = $x->compile();
			}

			//------------------------------ load form
			$this->data->classes = $classes;
		}
	}
}
?>