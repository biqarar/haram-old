<?php
class controller extends main_controller
{

	public function config()
	{
		//------------------------------ city api (get list of city in one province)
		$this->listen(array(
			"max" => 100,
			"url" => "/.*/"
			),
			function ()
			{

				$this->access = true;
			}
		);
	}
}
?>