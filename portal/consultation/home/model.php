<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class model extends main_model {
	public function sql_curl($curl = false) {
		return $this->sql()->tablePosts()->whereCurl($curl)->select()->assoc('id');
	}
}
?>