 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "bridge";

		//-------------- check branch
		$this->sql(".branch.users", $this->xuId("usersid"));

		//------------------------------ list of classes
		$bridge = $this->sql(".list", "bridge", function ($query, $users_id) {
			$query->whereUsers_id($users_id);
		}, $this->xuId("usersid"))->compile();

		$this->data->list = $bridge;
	}
}
?>