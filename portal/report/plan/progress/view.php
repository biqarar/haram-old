<?php
/**
 *
 */
class view extends main_view  {
	public function config() {
		//------------------------------  global
		$this->global->page_title = _("وضعیت نمرات کلاس");

		//------------------------------  set users_id
		$users_id  = $this->xuId();

			//----------------------- check banch

		$plan_id = $this->xuId("id");
		$this->data->plan_id = $plan_id;
		$this->sql(".branch.plan",$plan_id);
		$this->data->plan_detail = $this->sql(".assoc.foreign" , "plan", $plan_id , "name" , "id");

		//------------------------------ get detail classes
		// $this->classesDetail();
		$chart = $this->sql("#progress", $plan_id);
		$this->data->chart = $chart;

		$absence = $this->sql("#absence", $plan_id);
		$this->data->absence = $absence;

		$list = $this->sql(".list", "classification", function($q){
			$q->fieldDate_entry()->fieldDate_delete();
			$q->joinClasses()->whereId("#classification.classes_id")->andStatus("running")->fieldId();
			$q->joinPerson()->whereUsers_id("#classification.users_id")
			->fieldName()
			->fieldFamily()
			->fieldFather()
			->fieldUsers_id();
			$q->joinPlan()->whereId("#classes.plan_id")->andId($this->xuId("id"))->fieldId();
			$q->joinBridge()->whereUsers_id("#classification.users_id")->andTitle("mobile")->fieldValue();
			$q->whereBecause("absence");

		});

		$list = $list->compile();

		if(isset($list['list']) && is_array($list['list']))
		{
			foreach ($list['list'] as $key => $value)
			{
				if(isset($value['users_id']))
				{
					$list['list'][$key]['users_id'] = $this->tag('a')->class('icoshare')->href('users/learn/id='. $value['users_id'])->render();
				}
				if(isset($value['id']))
				{
					$list['list'][$key]['id'] = $this->tag('a')->vtext($value['id'])->href('classification/class/classesid='. $value['id'])->render();
				}
			}
		}

		$this->data->list = $list;

	}
}
?>