<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class controller extends main_controller {
	public function config() {
		new loadfile_cls(config_lib::$url);
		var_dump("fuck");exit();
	}
}
?>