<?php
/**
 * @author REZA MOHITI <rm.biqarar@gmail.com>
 */

class view extends main_view{
	public function config(){
		$this->global->page_title='files';
		$this->global->url = $this->uStatus(true);
		$hidden = $this->form("#hidden")->value("files");
		$id = $this->form("hidden")->name("id")->label("id")->id("id");
		$crop_size = $this->form("hidden")->name("crop_size")->label("crop_size")->id("crop_size");

		$tag = $this->form("select")->name("tag")->label("tag");
		$user = $this->form("text")->label("user")->id("type-combo")->addClass("notselect");
		$title = $this->form("text")->label("title")->name("title");
		$description = $this->form("textarea")->label("description")->name("description");
		$file =  $this->form("file")->name("file")->label("file");
		$submit = $this->form("#submitedit")->value("send");
		
		$base = config_lib::$surl['base'];
		$tagList = $this->sql("@get_tag", $base);
		foreach ($tagList as $key => $value) {
			$tag->child()->value($value->id)->label($value->tag)->data_condition($value->condition);
		}

		$files = array();
		
		$files[] = $hidden->compile();
		$files[] = $id->compile();
		$files[] = $crop_size->compile();
		$files[] = $tag->compile();
		$files[] = $user->compile();
		$files[] = $title->compile();
		$files[] = $description->compile();
		$files[] = $file->compile();
		$files[] = $submit->compile();
		
		$this->data->files = $files;
		$this->data->baseOption = $base;
	}
}
?>