<?php
class main_view{
	public $formIndex = array();
	public final function __construct($controller){
		$this->controller = $controller;
		$this->data = new aDATA();
		$this->data->global = new aData();
		$this->global = $this->data->global;

		// *********************************************************************** Site Global Variables
		$this->global->domain             = DOMAIN;
		$this->global->path               = PATH;
		$this->global->site_url           = 'http://'.DOMAIN.PATH;
		$this->global->site_static        = 'http://'.DOMAIN.PATH.'static/';

		$this->global->site_title         = "مرکز قرآن و حدیث";
		$this->global->site_desc          = "پورتال جامع مرکز قرآن و حدیث آستان مقدس حضرت فاطمه معصومه";
		$this->global->page_title_spliter = true;
		$this->global->page_title         = $this->global->site_title;
		$this->global->page_desc          = $this->global->site_desc;

		//$this->global->host = host;
		//$this->global->ahost = host.path;


		// $this->data->debug = DEBUG;

		$menu =  new menu_cls;
		$this->global->menu = $menu->list_menu();

		//------------------------------ set url if status == edit >> table/stauts=edit/id=\d
		$this->setUrl();

		// *********************************************************************** Other ...
		$this->form = (object) "form";
		unset($this->form->scalar);
		$this->data->form = $this->form;
		$this->data->layout = './main/display.html';
		// $this->data->homeLayout = './main/home.html';

		$this->data->macro['forms'] = 'macro/forms.html';
		$this->data->macro['dtable'] = 'macro/dtable.html';
		$this->data->macro['list'] = 'macro/tagMaker.html';
		if(isset($_SESSION['error']) && isset($_SESSION['error'][config_lib::$URL]) && isset($_SERVER['HTTP_REFERER'])){
			$this->data->debug = $_SESSION['error'][config_lib::$URL];
			unset($_SESSION['error'][config_lib::$URL]);
		}

		if(method_exists($this, 'config')){
			$this->config();
			$this->checkRedirect();
		}
		$this->compile();


	}



	public final function form($type = false, $args = array()){
		$this->data->extendForm = true;
		$cForm = new forms_lib();
		$form = $cForm->make($type, $args);
		array_push($this->formIndex, $form);
		if(preg_match("/^@(.*)$/", $type, $name)){
			$this->form->{$name[1]} = $form;
		}
		return $form;
	}

	public final function compile(){
		if(isset($this->data->form)){
			$forms = $this->data->form;
			foreach ($forms as $key => $value) {
				if(method_exists($value, "compile")){
					$this->data->form->$key = $value->compile();
				}else{
					$this->data->form->$key = array();
					foreach ($value as $ckey => $cvalue) {
						if(!method_exists($cvalue, 'compile')){
							echo "$ckey not found compile";
							exit();
						}
						$this->data->form->{$key[$ckey]} = $cvalue->compile();
					}
				}
			}
		}
		$this->Localy();

		$Header = apache_request_headers();
		$tmpname		= config_lib::$class.'/'.config_lib::$method;
		$tmpname 		.= (config_lib::$child !='') ? '/'.config_lib::$child : '';
		$tmpname 		.='/display.html';
		if(ifAjax()){
			$this->data->layout = './main/xhr.html';
			echo '<div id="global">'.json_encode($this->global->compile())."</div>\n";
		}
		require_once core.'Twig/lib/Twig/Autoloader.php';
		Twig_Autoloader::register();
		$loader		= new Twig_Loader_Filesystem(content);

		$twig		= new Twig_Environment($loader);
		$this->main_Extentions($twig);
		$template		= $twig->loadTemplate($tmpname);
		$template ->	display($this->data->compile());
	}

	public final function main_Extentions($twig){
		$twig->addFilter($this->twig_fcache());
		$twig->addFilter($this->twig_lang());
		$twig->addFilter($this->twig_nameFamily());

	}
	public function twig_fcache(){
		return new Twig_SimpleFilter('fcache', function ($string) {
			if(file_exists($string)){
				return $string.'?'.filemtime($string);
			}
		});
	}

	public function twig_lang(){
		return new Twig_SimpleFilter('lang', function ($string) {
			if(!empty($string)){
				$s = preg_split("/,/", $string);
				$a = array();
				foreach ($s as $key => $value) {
					array_push($a, gettext($value));
				}
				return join($a, '، ');
			}else{
				return $string;
			}
		});
	}
	/**
	* @author reza mohiti
	* return name and family by get users_id
	*/
	public function twig_nameFamily(){
		return new Twig_SimpleFilter('nameFamily', function ($users_id) {
			return $this->sql(".assoc.foregn", "person", $users_id ,  "name" ,"users_id")
				. " " . $this->sql(".assoc.foregn", "person", $users_id ,  "family" ,"users_id");
		});
	}

	public function __call($name, $args){
		if(preg_grep('/^'.$name.'$/', $this->controller->__autocallMethod)){
			return call_user_func_array(array($this->controller, $name), $args);
		}
	}

	public function __get($name){
		if(preg_grep('/^'.$name.'$/', $this->controller->__autogetProperty)){
			return $this->controller->$name;
		}else{
			return false;
		}
	}

	public function Localy(){
		/**
		 * forms
		 */
		$this->global->page_title = _($this->global->page_title);

	}

	public function listBranch($f, $type = "select") {
		$branch_list = $this->sql(".branch.get_list");
		// var_dump($branch_list);
		if($type == "select"){
			$branch = $this->form("select")->name("branch_id")->classname("select-branch")->label("branch");
			foreach ($branch_list as $key => $value) {
				$branch->child()->name($value['branch_name'])->label($value['branch_name'])->value($value["branch_id"]);
			}
			// $branch->child(0)->selected("selected");
			$f->add("branch_id", $branch);
			$f->atFirst("branch_id");
		}elseif ($type == "chekbox") {
			foreach ($branch_list as $key => $value) {
				$branch_id = $value['id'];
				$branch = $this->form("checkbox")->name("branch_$branch_id")->label($value['name'])->value($branch_id);
				$f->add("$branch_id", $branch);
				$f->atEnd("branch");
			}
		}

	}

	public function topLinks($links = false) {

		if($links) {
			$this->data->topLinks = $links;
		}
	}

	public function setUrl() {
		if($this->urlStatus() == "edit") {
			$this->global->url = "status=".$this->urlStatus() . "/id=" . $this->xuId();
		}else{
			$this->global->url = "status=".$this->urlStatus();
		}
	}

	/**
	* make link to show detail record table
	*/
	public function detailLink($table = flase) {
		return $this->link($table . "/status=detail/id=%id%", "href" , "icomore");
	}

	 /**
	 * make link to edit record table
	 */
	 public function editLink($table = flase) {
	 	return $this->link($table . "/status=edit/id=%id%", "href" , "icoedit");
	 }

	/**
	* make link whit costum url
	*/

	public function link($url = flase , $attr = "href" , $cssClass = "icomore") {
	 	return $this->tag("a")
	 	->addClass($cssClass)
	 	->attr($attr, $url);
	 	// ->attr("target", "_blank");
	}

	public function classeTopLinks($classes_id = false){
			if(isset(config_lib::$surl['classesid'])){
				$classesid = $this->xuId("classesid");
			}elseif(config_lib::$surl['status'] == 'detail'){
				$classesid = $this->xuId();
			}
			if($classes_id) $classesid = $classes_id;
		$this->topLinks(
				array(
					array("title"=> "کلاسها", 'url' =>"classes/status=list"),
					array("title"=> "کلاسبندی", 'url' =>"classification/class/classesid=" . $classesid),
					array("title"=> "غیبت", 'url' =>"absence/classes/classesid=" . $classesid),
					array("title"=> "نمرات", 'url' =>"score/classes/classesid=" . $classesid),
					array("title"=> "شهریه", 'url' =>"price/classes/classesid=" . $classesid),
					array("title"=> "اطلاعات", 'url' =>"classes/status=detail/id=" . $classesid),
					array("title"=> "اصلاح", 'url' =>"classes/status=edit/id=" . $classesid),
					array("title"=> "نمودار", 'url' =>"classification/progress/id=" . $classesid),
					array("title"=> "گزارش", 'url' =>"classification/report/id=" . $classesid),
					// array("title"=> "چاپ", 'url' =>"classification/printlist/classesid=" . $this->xuId("classesid")),
					)
			);
	}
	/**
	*	some field in the classes table must be change (foreign) to other field in other table
	*/
	public function classesDetail($classes_detail = false) {
		// //------------------------------ get detail classes
		if(isset(config_lib::$surl['classesid']) || (config_lib::$surl['status'] == 'detail')){

			$this->classeTopLinks();
			//------------------------------ classes id
			$classesid = (isset(config_lib::$surl['status'])
				&& config_lib::$surl['status'] == 'detail') ? $this->xuId() : config_lib::$surl['classesid'];

			$classes_detail = $this->sql(".classesDetail", $classesid);
			if($classes_detail){
				$this->global->classesid = $classes_detail['classesid'];
				$this->global->page_title = $classes_detail['page_title'];
				$this->data->list = $classes_detail['list'];
			}
		}
	}

	/**
	*	some field in the classes table must be change (foreign) to other field in other table
	*/
	public function detailClasses($classes_detail = false) {

		if(isset($classes_detail['list'])){
			foreach ($classes_detail ['list'] as $key => $value) {
				$classes_detail ['list'][$key]['plan_id']   = $this->sql(".assoc.foreign", "plan", $value["plan_id"], "name");
				$classes_detail ['list'][$key]['teacher']   =
				$this->sql(".assoc.foreign", "person", $value["teacher"], "name", "users_id") . ' ' .
				$this->sql(".assoc.foreign", "person", $value["teacher"], "family", "users_id");
				$classes_detail ['list'][$key]['place_id']  = $this->sql(".assoc.foreign", "place", $value["place_id"], "name");
			}
		}
		return $classes_detail;
	}

	public function detailCol($table, $list, $html) {
		if($this->colPermission($table, "select")) {
			return	$list->addCol("detail", "detail")->select(-1, "detail")->html($html);
		}else{
			return $list;
		}
	}

	public function editCol($table, $list, $html) {
		if($this->colPermission($table, "update")) {
			return	$list->addCol("edit", "edit")->select(-1, "edit")->html($html);
		}else{
			return $list;
		}
	}


	public function colPermission($table, $operat) {
		if(global_cls::supervisor()) return true;
		if(isset($_SESSION['my_user']['permission']['tables'][$table][$operat]) &&
			$_SESSION['my_user']['permission']['tables'][$table][$operat] == 'public'){
			return true;
		}
		return false;
	}

	public function dtable($url, $fields){
		$this->data->Extend_dtable = true;
		$array = array();
		foreach ($fields as $key => $value) {
			$array[] = gettext($value);
		}
		return array('url'=> $url, 'fields'=>$array);
	}

	public function check_users_type($users_id = false) {
		if($this->login() && isset($_SESSION['my_user']['type'])) {
			list($access, $msg) = $this->checkPermissions();
			if(!$access){
				if(($_SESSION['my_user']['type'] == "teacher" || $_SESSION['my_user']['type'] == "operator") && $_SESSION['my_user']['id'] != $users_id) {
					page_lib::access("what are you looking for ?");
				}
			}
		}
	}

}

class aDATA{
	function compile(){
		return ((array) $this);
	}
}
?>