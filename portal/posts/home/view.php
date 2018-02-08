<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {
	public function config() {
		$data = $this->sql(".postsList", $this->uId(1));
		$this->global->page_title = $data['title'];
		 $this->data->posts = $data['list'];
		 $this->global->main_menu = array(
		 	array('href' => 'login', 'title' => 'ورود کاربران'),
		 	array('href' => 'graduate/', 'title' => 'دانش آموختگان'),
		 	array('href' => 'posts/add', 'title' => 'اخبار بیشتر'),
		 	array('href' => 'posts/add', 'title' => 'اخبار بیشتر'),
		 	array('href' => 'posts/add', 'title' => 'اخبار بیشتر'),
		 	array('href' => 'posts/add', 'title' => 'اخبار بیشتر'),
		 	array('href' => 'posts/add', 'title' => 'اخبار بیشتر'),
		 	array('href' => 'posts/add', 'title' => 'اخبار بیشتر')
		 	);
		 // var_dump($this->global->main_menu);
		 // exit();
	}
}
?>