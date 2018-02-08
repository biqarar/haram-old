<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */


class view extends main_view {

	public function config(){

		//------------------------------ global
		$this->global->page_title = "bridge";

		//------------------------------ load bridge form
	    $f = $this->form('@bridge', $this->urlStatus());

	    //------------------------------ set users_id
	    $users_id =  $this->xuId("usersid");

	    //------------------------------ put users_id in hidden input to get in model.php
	    $f->users_id->value($users_id);

	    //------------------------------ list of bridge fo this user
    	$this->data->list = $this->sql(".list", "bridge" , function($query, $users_id){
    		$query->whereUsers_id($users_id);
    	}, $users_id)

    	//------------------------------ edit link
    	->addCol("edit", "edit")
    	->select(-1 , "edit")
    	->html($this->link("bridge/status=edit/usersid=$users_id/id=%id%" , "href", "icoedit"))
    	->compile();

    	//------------------------------ load edit form
	    if($this->urlStatus() == "edit"){
			$this->sql(".edit", "bridge", $this->xuId(), $f);	    
	    }

	}
}
?>