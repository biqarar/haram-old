<?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = _('selectbranch');

		$f = $this->form("@users_branch");

		$f->add("hidden", "hidden")->name("_post")->value("selectbranch");

		$f->add("select_branch", "select")->name("select_branch");

		foreach ($this->sql(".branch.get_users_branch") as $key => $value) {
			$f->select_branch->child()
				->name("selectbranch_" .  $value['branch_id'])
				->value($value['branch_id'])
				->label(_($value['type']) . " در " . $value['name']);
		}
		if(isset($_SESSION['supervisor'])){
			$this->data->supervisor = true;
			$this->data->name_family = $this->sql(".userNameFamily",$_SESSION['my_user']['id']);

			$f->add("username","text")
				->name("supervisor")->label("ورود با نام کاربری")->pl("نام کاربری مورد نظر");

			$f->select_branch->child()
				->name("selectbranch_supervisor")
				->value("supervisor_" . $_SESSION['supervisor'])
				->label("سوپر وایزر");
		}

		$f->add("select","submit")->name("select")->value(_("ورود   با  سمت  انتخابی"));

		$f->add("logout","submit")->name("logout")->value(_("خروج از حساب کاربری"));

		$f->remove("submit");
	}
}
?>