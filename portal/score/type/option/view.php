<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view
{

	public function config()
	{
		//------------------------------ global
		$this->global->page_title='score_type';

		//------------------------------ load form
		$f = $this->form("@score_type", $this->urlStatus());


		//----------------- check branch
		$this->sql(".branch.score_type", $this->xuId());

		//------------------------------ edit form
		$this->sql(".edit", "score_type", $this->xuId(), $f);

		//------------------------------ list of score_type
		$this->data->dataTable = $this->dtable("score/type/status=apilist/",
			array("id","plan","title", "min","max","type","status", "edit"));
	}
}
?>