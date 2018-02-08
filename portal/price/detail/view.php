 <?php
/**
 * @author Reza Mohiti <rm.biqarar@gmail.com>
 */
class view extends main_view {

	public function config() {
		//------------------------------ globals
		$this->global->page_title = "price";

		$usersid = $this->xuId("usersid");
		
		//----------------------- chec branch
		$this->sql(".branch.users",$usersid);

		$this->topLinks(array(
				array("title" => "آموزش", "url" => "users/learn/id=$usersid"),
				array("title" => "ثبت", "url" => "price/status=add/usersid=$usersid"),
				array("title" => "نمایش", "url" => "price/status=detail/usersid=$usersid")
			));
		//------------------------------ list of classes
		$price = $this->sql(".list", "price", function ($query) {
			$query->whereUsers_id($this->xuId("usersid"))->andVisible(1);
			$query->joinPrice_change()->whereId("#price.title")->fieldName("changeName")->fieldType("priceChangeType");
			$query->joinPerson()->whereUsers_id("#price.users_id")->fieldName()->fieldFamily();		
		})
		->removeCol("branch_id,title,status,users_id,visible")

		->addColFirst("changeName", "changeName")
		->addColFirst("priceChangeType", "priceChangeType")
		->addColFirst("value", "value")
		->addColFirst("date", "date")
		->addColFirst("family", "family")
		->addColFirst("name", "name")
		->addColFirst("id", "id")
		->addColEnd("edit", "edit")
		->compile();

		foreach ($price['list'] as $key => $value) {
			$price['list'][$key]['edit'] = $this->tag("a")->href("price/status=edit/id=".$price['list'][$key]['id'])->class("icoedit")->render();
			$plan_id = $this->sql(".assoc.foreign", "classes", $price['list'][$key]['transactions'] , "plan_id");
			$plan_name = $this->sql(".assoc.foreign", "plan", $plan_id , "name");

			if($value['changeName'] == 'شرکت در کلاس' || $value['changeName'] == 'پرداخت دوره ای' ) {

				$detail = $this->sql(".classes.detail", $value['transactions']);
				$str = "کد کلاس: " . $detail['id'] . " \n";
				$str .= "طرح: " . $detail['planname'] . " \n";
				$str .= "نام استاد: " . $detail['teacherName'] . " " . $detail['teacherFamily'] . " \n";
				$str .= "مدرس: " . $detail['place'] . " \n";
				$str .= "نام کلاس: " . $detail['name'] . " \n";
				$str .= "وضعیت کلاس : " . _($detail['status']) . " \n";

				$price['list'][$key]['transactions'] = $this->tag("a")
					->href("classes/status=detail/id=" . $value['transactions'])
					->class("icoclass")->title("جهت ثبت در کلاس استفاده شده است \n" . $str)->render() . " $plan_name  " ;

			}
		}

		$this->data->list = $price;
	}
}
?>