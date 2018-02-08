 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "person";

		$list = $this->sql(".list", "person", function($query){
			$query->whereUsers_id($this->SESSION_usersid());
		})->compile();
		$this->data->list = $list;
	}
}
?>