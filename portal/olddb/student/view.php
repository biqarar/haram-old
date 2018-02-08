 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "student";


		//------------------- check branch
		$this->sql(".branch.olddb", "student", $this->xuId());
		
		//------------------------------ list of classes
		$student = $this->sql(".olddblist", "student", $this->xuId());



		$this->data->list = $student;
	}
}
?>