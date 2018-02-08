<?php
/**
 * @author reza mohitit rm.biqarar@gmail.com
 */


class view extends main_view {

	public function config(){

		//------------------------------ global
		$this->global->page_title = "bridge";

		//------------------------------ check users (if teacher, can not be display by changing id)
		$this->check_users_type($this->xuId("usersid"));

		//------------------------------ load bridge form
	    $f = $this->form('@bridge', $this->urlStatus());

	    //------------------------------ set users_id
	    $users_id =  ($this->xuId("usersid"))? $this->xuId("usersid") : $this->sql("#users_bridge", $this->xuId());

	    //--------------- check branch
	    $this->sql(".branch.users",$users_id);

	    $this->global->users_id =  $users_id;
	    //------------------------------ put users_id in hidden input to get in model.php
	    $f->users_id->value($users_id);

	    //------------------------------ list of bridge fo this user
    	$list = $this->sql(".list", "bridge" , function($query, $users_id){
    		$query->whereUsers_id($users_id);
    		$query->joinPerson()->whereUsers_id("#bridge.users_id")->fieldName()->fieldFamily();
    	}, $users_id);

    	$list->removeCol("users_id,id");
    	
    	$list->addColFirst("family", "family");
    	$list->addColFirst("name", "name");

    	//------------------------------ edit link
    	$list = $this->editCol("bridge", $list, $this->link("bridge/status=edit/usersid=$users_id/id=%id%" , "href", "icoedit"));

    	$this->data->list = $list->compile();

    	//------------------------------ load edit form
	    if($this->urlStatus() == "edit"){

			$this->sql(".edit", "bridge", $this->xuId(), $f);	    
	    }

	}
}
?>