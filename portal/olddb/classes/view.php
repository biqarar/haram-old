 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "oldclasses";
	

		//------------------- check branch
		$this->sql(".branch.olddb", "oldclasses", $this->xuId());
		
		//------------------------------ list of classes
		$oldclasses = $this->sql(".olddblist", "oldclasses", $this->xuId());


		$this->data->list = $oldclasses;
	}
}
?>