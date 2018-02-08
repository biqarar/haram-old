 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "oldclassification";

		//------------------- check branch
		$this->sql(".branch.olddb", "oldclassification", $this->xuId());
		
		//------------------------------ list of classes
		$oldclassification = $this->sql(".olddblist", "oldclassification", $this->xuId());
		
		foreach ($oldclassification['list'] as $key => $value) {
			$oldclassification['list'][$key]['oldclasses'] = $this->sql("#oldclasses_detail", $value['oldclasses']);
		}

		$this->data->list = $oldclassification;
	}
}
?>