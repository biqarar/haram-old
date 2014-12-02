 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "oldclasses";

		//------------------------------ list of classes
		$oldclasses = $this->sql(".list", "oldclasses", function ($query, $id) {
			$query->whereUsers_id($id);
		}, $this->xuId())->compile();


		$this->data->list = $oldclasses;
	}
}
?>