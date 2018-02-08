<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view {

	public function config() {
		//------------------------------ global
		// $this->global->page_title = "certification";

		// //------------------------------ load form
		// $f = $this->form('@certification', $this->urlStatus());
		// // var_dump($f); exit();

		// //------------------------------ list of branch
		// // $this->listBranch($f);

		// //------------------------------ remove branch_id because list of branch loaded
		// // $f->remove("branch_id");

		// //----------------------------- check branch
		// $this->sql(".branch.certification", $this->xuId());
		
		// //------------------------------ edit form
		// $this->sql(".edit", "certification", $this->xuId(), $f);

		// // //------------------------------ list of certification
		// // $this->data->list = $this->sql(".list","certification")
		// // ->addColEnd("edit","edit")->select(-1, "edit")->html($this->editLink("certification"))
		// // ->compile();

		// $this->data->dataTable = $this->dtable('certification/status=api/', array('name', "description" , "multiclass","edit"));

	}
}
?>