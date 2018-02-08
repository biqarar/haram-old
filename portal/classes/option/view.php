<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */

class view extends main_view {

	public function config(){
		//------------------------------ global
		$this->global->page_title ="classes";

		//------------------------------ load form
		$f = $this->form("@classes", $this->urlStatus());
		$f->remove("status");
		//------------------------------ list of branch
		// $this->listBranch($f);

		//------------------------------ edit form
		if($this->urlStatus() == "edit") {

			//---------------- check branch
			$this->sql(".branch.classes", $this->xuId());

			$this->sql(".edit", "classes", $this->xuId(), $f);
			
			// $f->teacher->attr['value'] = ($this->sql("#find_teacher_name", $f->teacher->attr['value']));

			$week_days = $this->sql(".edit.query", "classes", $this->xuId())->assoc("week_days");
			$week_days = preg_split("/\,/", $week_days);
			foreach ($f->week_days->child as $key => $value) {
				if(preg_grep("/". $value->attr['id'] ."/", $week_days)){
					$value->checked("checked");
				}
			}
		}
	}
}
?>