<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model{

	// public function sql_a(){
	// 	$x = $this->sql()->tableUsers()->whereId("1425")->select();
	// 	var_dump($x);
	// 	var_dump("expression");
	// }

	// public function post_add_group(){
	// 	var_dump(post::name());
	// 	die();
	// 	$sql = $this->makeQuery()->insert();
	// 	$this->commit(function() {
	// 		debug_lib::true("[[insert group successful]]");
	// 	});
	// 	$this->rollback(function() {
	// 		debug_lib::fatal("[[insert group failed]]");
	// 	});
	// }

	// public function post_edit_group(){
	// 	$sql = $this->makeQuery()
	// 			->whereId($this->uId())
	// 			->update();
	// 	$this->commit(function() {
	// 		debug_lib::true("[[update group successful]]");
	// 	});
	// 	$this->rollback(function() {
	// 		debug_lib::fatal("[[update group failed]]");
	// 	});
	// }

	// public function sql_tt(){
	// 	$sq = $this->sql()
	// 		->tableUsers()
	// 		->likeId("%25%")->select();
	// }
}
?>