<?php
class forms_Extends_cls extends forms_lib{
	function __construct(){
		$this->hidden = $this->make("hidden")->name("_post");
		
		// username contain email or mobile number
		$this->username = $this->make("text")->name("username")->label("username");
		$this->username->validate()->username()->form->username("username incorrect");

		// password
		$this->password = $this->make("password")->name("password")->label("password");
		$this->password->validate()->password()->form->password("password incorrect");

		// only accept numbers
		$this->number = $this->make("text")->name("number")->label("number");
		$this->number->validate()->number()->form->number("number incorrect");

		// only accept prices
		$this->price = $this->make("text")->name("price")->label("price");
		$this->price->validate()->price()->form->price("price incorrect");

		// check for iranian nattional code
		$this->nationalcode = $this->make("text")->name("nationalcode")->label("nationalcode");
		$this->nationalcode->validate()->nationalcode()->form->nationalcode("nationalcode incorrect");

		// accept text
		$this->unicodetext = $this->make("text")->name("text")->label("Text");
		$this->unicodetext->validate();

		// unicode text accept Title
		$this->text_title = $this->make("text")->name("title")->label("Title");
		$this->text_title->validate();

		// unicode text accept Slug
		$this->text_slug = $this->make("text")->name("slug")->label("Slug");
		$x = $this->text_slug->validate();
		$x->slug(function(){
			if(preg_match("[\/\\\"'\.]", $this->value)) return false;
		})->form->slug("hi")->sql->unique("hiss");

		// unicode text accept Slug
		$this->text_website = $this->make("text")->name("website")->label("Website");
		$this->text_website->validate()->website();

		// Description
		$this->text_desc = $this->make("textarea")->name("desc")->label("description");
		$this->text_desc->validate()->description()->form->description("description overflow");

		// only accept english text
		$this->entext = $this->make("text")->name("entext")->label("English Text");
		$this->entext->validate()
		->reg("/^([a-z]{2,}\s?)+[a-z]$/Ui")->form->reg("text must be english");

		// only accept farsi text
		$this->fatext = $this->make("text")->name("fatext")->label("fatext");
		$this->fatext->validate()->farsi()->form->farsi("text must be persian");
		
		// Accept Date
		$this->date = $this->make("text")->name("date")->label("date")->date("date");
		$this->date->validate()->date()->form->date("date incorrect");
		
		// Accept Time
		$this->time = $this->make("time")->name("time")->label("time");
		$this->time->validate()->time()->form->time("time incorrect");

		// Accept Email
		$this->email = $this->make("email")->name("email")->label("email");
		$this->email->validate()->email()->form->email("email incorrect");
		
		// submit
		$this->submitadd = $this->make("submit")->value("insert");
		$this->submitedit = $this->make("submit")->value("edit");
		$this->submitlogin = $this->make("submit")->value("login");

		// deny robot to submit forms
		$this->robot = $this->make("robot")->name("name")->label("User Name")->pl("Please Enter Your Name!");
		$this->check = $this->make("checkbox");	
	}
}
?>