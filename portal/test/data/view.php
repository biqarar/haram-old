<?php
class view extends main_view{
	function config(){
		$x = $this->tag("div")->name("parent")->data_x("10");
		$x->addChild("a");
		$x->addChild("br");
		$x->render();
		exit();
	}
}
?>