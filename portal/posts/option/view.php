<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com 
*/
class view extends main_view {
	
	function config(){
		$this->global->page_title  = "posts";
		$this->global->url = $this->uStatus(true);
		$f = $this->form("@posts", $this->uStatus());
		$filebox = $this->form("button")->value("file_box_load")->classname("filebox");
		$f->add("filebox", $filebox);
		$f->after("filebox", 'title');

		$size = $this->form("hidden")->value("0 0 150 150")->classname("img-crop")->name("imgCrop");
		$f->add("size", $size);
		$f->after("filebox", 'title');

		$imgFile = $this->form("hidden")->name("postImg");
		$f->add("imgFile", $imgFile);

		$file = $this->sql(".files.form", $this, array("base" => $f, "group" => "news"));
		$close = $this->form("button")->value("close")->classname("filebox-close");
		$file->add("close", $close);
		if(is_int($this->uId())) {
			$this->sql(".edit", "posts", $this->uId(), $f);
		}
	}
} 
?>