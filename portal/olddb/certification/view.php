 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "certification";

		//------------------- check branch
		$this->sql(".branch.olddb", "oldcertification", $this->xuId());
		
		//------------------------------ list of classes
		$certification = $this->sql(".olddblist", "oldcertification", $this->xuId());

		$this->data->list = $certification;
	}
}
?>