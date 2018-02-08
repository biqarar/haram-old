 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "oldprice";


		//------------------- check branch
		$this->sql(".branch.olddb", "oldprice", $this->xuId());
		
		//------------------------------ list of classes
		$oldprice = $this->sql(".olddblist", "oldprice", $this->xuId());

		
		$this->data->list = $oldprice;
	}
}
?>