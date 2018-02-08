<?php
class adminView_cls extends main_view{
	public function config(){
		$this->data->module = $this->url_method();
		$this->global->page_title = ucfirst($this->data->module);

		$this->data->datarow = $this->sql("@datarow");
		if(method_exists($this, "options")) $this->options();
	}
}