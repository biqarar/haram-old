<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view
{
	/**
	 * { function_description }
	 */
	public function config()
	{
		//------------------------------ global
		$type = ($this->xuId("type") == "teacher") ? "teachers" : "operator";
		$this->global->page_title  = " لیست " . _($type);
		$status = ($this->xuId("status") == "activelist") ? "apiactivelist"  : "apilist";
		$this->data->dataTable = $this->dtable(
			'teacher/status='.$status.'/type=' . $this->xuId("type") . "/",
			array(
				'casecode',
				'name',
				'family',
				'father',
				'birthday',
				'nationalcode',
				'code',
				'شماره همراه',
				// 'marriage',
				// 'education_id',
				'detail',
				'person_extera')
			);

	}
}
?>