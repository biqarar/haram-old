<?php
class adminController_cls extends main_controller{
	public function config(){
		if(method_exists($this, "options")) $this->options();
		/**
		 * DONT REMOVE THID COMMENTS ! ***************************
		 * array(class, method, child)
		 * array("class" => class, ...)
		 * array('admin', 'locations', '', 'delete')
		 * OR function(){save(class, method, child)}
		 * try to use array
		 */

		// add user defined method for use in view, model and controller
		$this->addMethod('url_child');
		$this->addMethod('url_method');
		$this->addMethod('url_parameter');
		$this->addMethod('url_title');
		$this->addMethod('submit_title');		

	}

	/*
	 * this function check url and has two method for check address
	 * first compare current address with user entered value and return True or False
	 * second condition return the title of page
	 */
	function url_child($child="add")
	{
		return config_lib::$child;
	}

	function url_method($method=false)
	{
		return config_lib::$method;
	}

	function url_parameter()
	{
		if( isset(config_lib::$aurl[0]) && config_lib::$aurl[0] == 'edit' && isset(config_lib::$aurl[1]) )
			return (config_lib::$aurl[1]);
	}

	function url_title()
	{
		if(config_lib::$aurl[0] == 'add')
			return 'Add New';
		elseif(config_lib::$aurl[0] == 'edit')
			return 'Edit';
	}

	function submit_title()
	{
		if(config_lib::$aurl[0] == 'add')
			return 'Submit';
		elseif(config_lib::$aurl[0] == 'edit')
			return 'Save';
	}
}