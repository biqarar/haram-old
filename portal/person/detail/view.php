 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "person";

		//---------------------- check branch
		$this->sql(".branch.users", $this->xuId());
		
		//------------------------------ list of classes
		$person = $this->sql(".list", "person", function ($query, $id) {
			$query->whereUsers_id($id);
		}, $this->xuId())->compile();

		if(isset($person['list'][0])){

		$person['list'][0]['from'] = 
			$this->sql(".assoc.foreign", "city", 
					($person['list'][0]['from'] != '') ?
						$person['list'][0]['from'] : 0 , "name");

		$person['list'][0]['nationality'] = 
			$this->sql(".assoc.foreign", "country", 
					($person['list'][0]['nationality'] != '') ?
						$person['list'][0]['nationality'] : 0 , "name");

		$person['list'][0]['education_id'] = 
			$this->sql(".assoc.foreign", "education", 
					($person['list'][0]['education_id'] != '') ?
						$person['list'][0]['education_id'] : 0 , "section");

		}
		$this->data->list = $person;
	}
}
?>