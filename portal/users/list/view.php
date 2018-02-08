<?php
/**
 *
 */
class view extends main_view  {
	public function config() {
		//------------------------------ global
		$this->global->page_title  = "person_list";
		$type = $this->xuId("type");
		$type = $type ? $type : "learn";
		$this->data->dataTable = $this->dtable(
			"users/status=api/type=".$type."/",
			array(
				'casecode',
				'name',
				'family',
				'father',
				'birthday',
				// 'gender',
				'nationalcode',
				'code',
				// 'marriage',
				// 'education_id',
				'detail',
				$type)
			);
	}
}
?>