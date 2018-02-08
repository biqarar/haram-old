<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller{
	
	public $access = true;
	function config(){	
		

		$this->listen(array(
			"max" => 1,
			"url" => array("online")
			), array("race","online")
			
		);	


		
	}

}
?>