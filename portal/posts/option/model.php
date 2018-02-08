<?php 
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model {
	public function makeQuery() {
		return $this->sql()->tablePosts()
				->setTitle(post::title())
				->setGroup("1")
				->setShort(post::short())
				->setText(post::text())
				->setTime_spread(post::time_spread())
				->setEnd_spread(post::end_spread())
				->setCurl(post::curl());
	}

	public function post_add_posts() {
		$sql = $this->makeQuery()
				  ->insert();
		$id = $sql->LAST_INSERT_ID();
		$file = $this->sql(".files.getFile", post::postImg());
		if(!$file){
			debug_lib::fatal("file not found", "postImg", "form");
		}
		$filep = fileexec_cls::validation($file, true);
		$filep = query_files_cls::checkType('news', $filep);
		if(!$filep && $file){
			debug_lib::fatal("file incorrect", "postImg", "form");
		}
		if(!post::imgCrop() || !preg_match("/^(\d{1,})\s(\d{1,})\s(\d{1,})\s(\d{1,})$/", post::imgCrop(), $size) ){
			debug_lib::fatal("select size", "postImg", "form");
		}
		if(debug_lib::$status){
			array_shift($size);
			array_unshift($size, 100);
			array_unshift($size, 100);
			array_push($size, 200);
			if(!$copy = query_files_cls::imageCR($file, $size)){
				debug_lib::fatal("image resizer error", "postImg", "form");
			}
			$newsImg = $this->sql()->tableTable_files()
								->setTable('posts')
								->setRecord_id($id)
								->setFiles_id($file['id'])
								->setDescription('postsImage')
								->insert();
			if(!debug_lib::$status && $copy){
				unlink($copy);
			}
		}
		$this->commit(function($id = false) {
			debug_lib::true("[[insert posts successful]]");
		}, $sql);

		$this->rollback(function() {
			debug_lib::fatal("[[insert post failed]]");
		});
	}

	public function post_edit_posts() {
		$sql = $this->makeQuery()
				->whereId($this->uId())
				->update();
		$this->commit(function($id = false) {
			debug_lib::true("[[update posts successful]]");
		}, $sql);

		$this->rollback(function() {
			debug_lib::fatal("[[update posts failed]]");
		});
	}

	public function sql_readyForEdit($id = false) {
		return $this->sql()->tableposts()->whereId($id)->select()->assoc();
	}
}
?>