<?php
/**
 * @author reza mohiti rm.biqarar@gmail.com
 */
class view extends main_view{
	public function config(){
		$this->global->title = "اطلاعات مورد نیاز دوره ها";
		$this->global->url = $this->uStatus(true, true);
		$f = $this->form("@course_description", $this->uStatus(2));

		$this->sql(".edit", "course_description", $this->uId(3), $f);
	}
}
?>