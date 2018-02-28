<?php
/**
*
*/
class menu_cls
{

	/**
	* list of menu (not compile)
	*/
	static $menu      = array();

	/**
	* list of menu dropdown (not compile)
	*/
	static $dropdown  = array();

	/**
	* list of menu (compiled)
	*/
	static $list_menu = array();


	/**
	* compile menu
	* @return array
	*/
	public function list_menu()
	{

		if(!$this->usersid())
		{
			return array();
		}
		//------------------------------ make menu
		$this->menus();

		$make = false;

		foreach (self::$menu as $index => $value)
		{
			//------------------------------ key to load menu or no
			$make = false;

			//------------------------------ if tag of menu == public then do not neet to permission
			if(is_string($value['tag']) && $value['tag'] == "public")
			{
				$make = true;
			}
			elseif(is_string($value['tag']) && $value['tag'] != "public")
			{
				$make = false;
			}
			else
			{
				//------------------------------ check permission and make menu
				foreach ($value['tag'] as $table => $v)
				{
					foreach ($v as $oprator => $publicPrivate)
					{
						foreach ($publicPrivate as $i => $p)
						{
							if(isset($_SESSION['my_user']['permission']['tables'][$table]) && isset($_SESSION['my_user']['permission']['tables'][$table][$oprator]))
							{
								if($_SESSION['my_user']['permission']['tables'][$table][$oprator] == $p)
								{
									$make = true;
									break;
								}
							}
						}
						if($make) break;
					}
					if($make) break;
				}
			}

			//------------------------------ make menu and dropdown
			if($make || global_cls::supervisor())
			{
				self::$list_menu[$value['submenu']]["dropdown"][]
				=  array("url" => $value['url'] , "name" => $value["name"]);
			}
		}

		//------------------------------ sort menu (not run)
		$index = array(
			"home"       => 1,
			"teacher"    => 2,
			"user"       => 3,
			"class"      => 4,
			"attendance" => 5,
			"letters"    => 6,
			"share"      => 7,
			"settings"   => 8,
			"folder"     => 9,
			"media"      => 10
			);

		//------------------------------ translate menu caption whit gettext()
		foreach (self::$list_menu as $key => $value)
		{
			self::$list_menu[$key]["name"] = _($key);
			// self::$list_menu[$key]['index'] = $index[$key];
		}

		//------------------------------ return
		return self::$list_menu;
	}

	/**
	* make array menu
	*/
	public function menus()
	{
		//------------------------------ hoem
		self::$menu[]       = array(
		"submenu"           => "home",
		"url"               => 'profile',
		"name"              =>  _("home"),
		"tag"               => "public"
		);

		// //------------------------------ news
		// self::$menu[]    = array(
		// 	"submenu"       => "home",
		// 	"url"           => 'profile',
		// 	"name"          =>  _("news"),
		// 	"tag"           => "public"
		// 	);

		//------------------------------ permission add
		self::$menu[]       = array(
		"submenu"           => "home",
		"url"               => 'permission/status=add',
		"name"              =>  _("add permission"),
		"tag"               => array(
		"permission"        => array("insert" => array("public"))
		)
		);

		//------------------------------ city add
		self::$menu[]       = array(
		"submenu"           => "home",
		"url"               => 'city/status=add',
		"name"              =>  _("add city"),
		"tag"               => array(
		"city"              => array("insert" => array("public"))
		)
		);

		//------------------------------ province add
		self::$menu[]       = array(
		"submenu"           => "home",
		"url"               => 'province/status=add',
		"name"              =>  _("add province"),
		"tag"               => array(
		"province"          => array("insert" => array("public"))
		)
		);

		//------------------------------  education
		self::$menu[]       = array(
		"submenu"           => "home",
		"url"               => 'education/status=add',
		"name"              =>  _("add education"),
		"tag"               => array(
		"education"         => array("insert" => array("public"))
		)
		);

		//------------------------------  country
		self::$menu[]       = array(
		"submenu"           => "home",
		"url"               => 'country/status=add',
		"name"              =>  _("add country"),
		"tag"               => array(
		"country"           => array("insert" => array("public"))
		)
		);

		//------------------------------  branch
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'branch/status=add',
		"name"              => _("add branch"),
		"tag"               => array(
		"branch"            => array("insert" => array("public"))
		)
		);

		//------------------------------  group
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'group/status=add',
		"name"              =>  _("add group"),
		"tag"               => array(
		"group"             => array("insert" => array("public"))
		)
		);

		//------------------------------  plan
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'plan/status=add',
		"name"              =>  _("add plan"),
		"tag"               => array(
		"plan"              => array("insert" => array("public"))
		)
		);

		//------------------------------  course
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'course/status=add',
		"name"              =>  _("add course"),
		"tag"               => array(
		"course"            => array("insert" => array("public"))
		)
		);


		//------------------------------  hefz_lig
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'hefzlig/ligs',
		"name"              =>  _("ثبت مسابقات تیمی"),
		"tag"               => array(
		"hefz_ligs"         => array("insert" => array("public"))
		)
		);

		//------------------------------  hefz_lig
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'hefzlig/hefzgroup',
		"name"              =>  _("ثبت گروه های مسابقه"),
		"tag"               => array(
		"hefz_ligs"         => array("insert" => array("public"))
		)
		);

		//------------------------------  hefz_team
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'hefzlig/teams',
		"name"              =>  _("ثبت و مدیریت تیم"),
		"tag"               => array(
		"hefz_ligs"         => array("insert" => array("public"))
		)
		);



		//------------------------------  hefz_team
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'hefzlig/race',
		"name"              =>  _("مسابقه"),
		"tag"               => array(
		"hefz_ligs"         => array("insert" => array("public"))
		)
		);

		//------------------------------  hefz_team
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'hefzlig/ligs/status=show',
		"name"              =>  _("جدول نتایج"),
		"tag"               => array(
		"hefz_ligs"         => array("select" => array("public"))
		)
		);

		//------------------------------  hefz_team
		self::$menu[]       = array(
		"submenu"           => "share",
		"url"               => 'race/online',
		"name"              =>  _("نتایج زنده"),
		"tag"               => array(
		"hefz_ligs"         => array("select" => array("public"))
		)
		);

		//------------------------------  if the teacher complete the form, menu not show else show the menu

		//------------------------------  teacher show detail
		self::$menu[]       = array(
		"submenu"           => "teacher",
		"url"               => 'teacher/status=detail/id=' . $this->usersid(),
		"name"              =>  _("show detail"),
		"tag"               => $this->teacher_form("show")
		);

		//------------------------------  teacher extera form
		self::$menu[]       = array(
		"submenu"           => "teacher",
		"url"               => 'teacher/extera/status=add/usersid=' . $this->usersid(),
		"name"              =>  _("edit extera detail"),
		"tag"               => $this->teacher_form("person_extera")
		);

		// //------------------------------  teacher education
		// self::$menu[]    = array(
		// 	"submenu"       => "teacher",
		// 	"url"           => 'teacher/education/status=add/usersid=' . $this->usersid(),
		// 	"name"          =>  _("education information"),
		// 	"tag"           => $this->teacher_form("education_users")
		// 	);

		//------------------------------  teacher bridge
		// self::$menu[]    = array(
		// 	"submenu"       => "teacher",
		// 	"url"           => 'teacher/teachinghistory/status=add/usersid=' . $this->usersid(),
		// 	"name"          =>  _("teaching history"),
		// 	"tag"           => $this->teacher_form("teachinghistory")
		// 	);

		//------------------------------  teacher bridge
		self::$menu[]       = array(
		"submenu"           => "teacher",
		"url"               => 'teacher/bridge/status=add/usersid=' . $this->usersid(),
		"name"              =>  _("bridge"),
		"tag"               => $this->teacher_form("bridge")
		);

		//------------------------------  if the teacher complete the form, menu not show else show the menu

		//------------------------------   person
		self::$menu[]       = array(
		"submenu"           => "user",
		"url"               => "person/status=add",
		"name"              =>  _("add person"),
		"tag"               => array(
		"person"            => array("insert" => array("public"))
		)
		);
		//------------------------------  users list
		self::$menu[]       = array(
		"submenu"           => "user",
		"url"               => "users/status=list",
		"name"              =>  _("person list"),
		"tag"               => array(
		"person"            => array("select" => array("public")),
		"users"             => array("select" => array("public"))
		)
		);


		//------------------------------   bridge list
		self::$menu[]       = array(
		"submenu"           => "user",
		"url"               => "bridge/status=list",
		"name"              =>  _("bridge"),
		"tag"               => array(
		"bridge"            => array("select" => array("public"))
		)
		);

		//------------------------------   bridge list
		self::$menu[]       = array(
		"submenu"           => "user",
		"url"               => "users/status=list/type=branch",
		"name"              =>  _("تغییر شعبه فراگیر"),
		"tag"               => array(
		"users_branch"      => array("update" => array("public"))
		)
		);

		//------------------------------   bridge list
		self::$menu[]       = array(
		"submenu"           => "user",
		"url"               => "database/status=removeduplicate",
		"name"              =>  _("تلفیق پرونده"),
		"tag"               => $this->supervisor()
		);

		//------------------------------   bridge list
		self::$menu[]       = array(
		"submenu"           => "allteacher",
		"url"               => "teacher/status=activelist/type=teacher",
		"name"              =>  _("اساتید فعال"),
		"tag"               => array(
		"teacher"           => array("select" => array("public"))
		)
		);
		//------------------------------   bridge list
		self::$menu[]       = array(
		"submenu"           => "allteacher",
		"url"               => "teacher/status=list/type=teacher",
		"name"              =>  _("teacher list"),
		"tag"               => array(
		"teacher"           => array("select" => array("public"))
		)
		);


		//------------------------------   bridge list
		self::$menu[]       = array(
		"submenu"           => "allteacher",
		"url"               => "teacher/status=list/type=operator",
		"name"              =>  _("operator list"),
		"tag"               => array(
		"users"             => array("select" => array("public"))
		)
		);

		//------------------------------ classes add
		self::$menu[]       = array(
		"submenu"           => "class",
		"url"               => "classes/status=add",
		"name"              =>  _("add classes"),
		"tag"               => array(
		"classes"           => array("insert" => array("public"))
		)
		);

		//------------------------------ classes add
		self::$menu[]       = array(
		"submenu"           => "class",
		"url"               => "course/courseclasses/status=add",
		"name"              =>  _("course classes"),
		"tag"               => array(
		"courseclasses"     => array("insert" => array("public"))
		)
		);

		//------------------------------ classes list
		self::$menu[]       = array(
		"submenu"           => "class",
		"url"               => "classes/status=list/",
		"name"              =>  _("active classes list") ,
		"tag"               => array(
		"classes"           => array("select" => array("public"))
		)
		);
		//------------------------------ classes list
		self::$menu[]       = array(
		"submenu"           => "class",
		"url"               => "classes/status=list/type=allclasses",
		"name"              =>  _("all classes list") ,
		"tag"               => array(
		"classes"           => array("select" => array("public"))
		)
		);
		//------------------------------ classes list manage
		self::$menu[]       = array(
		"submenu"           => "class",
		"url"               => "classes/status=manage/",
		"name"              =>  _("manage classes") ,
		"tag"               => array(
		"classes"           => array("delete" => array("public"))
		)
		);
		//------------------------------ price add
		self::$menu[]       = array(
		"submenu"           => "price",
		"url"               => 'users/status=list/type=price',
		"name"              =>  _("add price"),
		"tag"               => array(
		"price"             => array("insert" => array("public"))
		)
		);
		//------------------------------ price add
		self::$menu[]       = array(
		"submenu"           => "price",
		"url"               => 'price/change/status=add',
		"name"              =>  _("manage price"),
		"tag"               => $this->supervisor()
		);

		//------------------------------  score
		self::$menu[]       = array(
		"submenu"           => "score",
		"url"               => 'score/type/status=add',
		"name"              =>  _("score type"),
		"tag"               => array(
		"score_type"        => array("insert" => array("public"))
		)
		);

		//------------------------------ price add
		self::$menu[]       = array(
		"submenu"           => "price",
		"url"               => 'classes/status=list/type=price',
		"name"              =>  _("manage classes price"),
		"tag"               => array(
		"pirce"             => array("select" => array("public"))
		)
		);

		//------------------------------  score
		self::$menu[]       = array(
		"submenu"           => "score",
		"url"               => 'score/calculation/status=add',
		"name"              =>  _("score calculation"),
		"tag"               => array(
		"score_calculation" => array("insert" => array("public"))
		)
		);
		// ------------------------------   price list

		self::$menu[]       = array(
		"submenu"           => "price",
		"url"               => "price/status=list",
		"name"              =>  _("price list"),
		"tag"               => array(
		"price"             => array("select" => array("public"))
		)
		);

		//------------------------------ classes list
		self::$menu[]       = array(
		"submenu"           => "score",
		"url"               => "classes/status=list/type=score",
		"name"              =>  _("insert classes score"),
		"tag"               => array(
		"score"             => array("select" => array("public"))
		)
		);

		//------------------------------ certification list
		self::$menu[]       = array(
		"submenu"           => "certification",
		"url"               => "certification/status=list",
		"name"              =>  _("certification list") ,
		"tag"               => array(
		"certification"     => array("select" => array("public"))
		)
		);

		//------------------------------ plan add
		self::$menu[]       = array(
		"submenu"           => "class",
		"url"               => "place/status=add",
		"name"              =>  _("add place"),
		"tag"               => array(
		"place"             => array("insert" => array("public"))
		)
		);

		//------------------------------ classes list
		self::$menu[]       = array(
		"submenu"           => "attendance",
		"url"               => "classes/status=list/type=absence",
		"name"              =>  _("attendance"),
		"tag"               => array(
		"absence"           => array("select" => array("public"))
		)
		);


		// //------------------------------ classes list
		// self::$menu[]    = array(
		// 	"submenu"       => "attendance",
		// 	"url"           => "classes/status=list/type=presence",
		// 	"name"          =>  _("presence"),
		// 	"tag"           => array(
		// 		"absence"   => array("select" => array("public"))
		// 		)
		// 	);

		//------------------------------ drafts add
		// self::$menu[]    = array(
		// 	"submenu"       => "folder",
		// 	"url"           => "files/type=files/status=add/base=users",
		// 	"name"          =>  _("upload users file"),
		// 	"tag"           => array(
		// 		"file"      => array("insert" => array("public"))
		// 		)
		// 	);


		// //------------------------------ drafts add
		// self::$menu[]    = array(
		// 	"submenu"       => "mail",
		// 	"url"           => "sms/drafts/status=add",
		// 	"name"          =>  _("add drafts"),
		// 	"tag"           => array(
		// 		"drafts"    => array("insert" => array("public"))
		// 		)
		// 	);

		// //------------------------------ drafts add
		// self::$menu[]    = array(
		// 	"submenu"       => "mail",
		// 	"url"           => "sms/send",
		// 	"name"          =>  _("send sms"),
		// 	"tag"           => array(
		// 		"sms"       => array("insert" => array("public"))
		// 		)
		// 	);


		//------------------------------ classes list
		self::$menu[]       = array(
		"submenu"           => "letters",
		"url"               => "report/status=add/",
		"name"              =>  _("add report"),
		"tag"               => $this->supervisor()
		);

		//------------------------------ classes list
		self::$menu[]       = array(
		"submenu"           => "letters",
		"url"               => "report/",
		"name"              =>  _("report"),
		"tag"               => array(
		"report"            => array("select" => array("public"))
		)
		);

		//------------------------------ posts add
		self::$menu[]       = array(
		"submenu"           => "letters",
		"url"               => "posts/status=add",
		"name"              =>  _("add posts"),
		"tag"               => array(
		"posts"             => array("insert" => array("public"))
		)
		);


		//------------------------------ public menu

		//------------------------------ (public) change password menu
		self::$menu[]       = array(
		"submenu"           => "settings",
		"url"               => 'changepasswd',
		"name"              =>  _("change password"),
		"tag"               => "public"
		);
		//------------------------------ (public) log out menu
		self::$menu[]       = array(
		"submenu"           => "settings",
		"url"               => 'login/selectbranch',
		"target"            => "_blank",
		"name"              =>  _("انتخاب شعبه"),
		"tag"               => $this->selectbranch()
		);
		//------------------------------ (public) log out menu
		self::$menu[]       = array(
		"submenu"           => "settings",
		"url"               => 'logout',
		"name"              =>  _("logout"),
		"tag"               => "public"
		);

	}

	public function selectbranch()
	{
		if(isset($_SESSION['my_user']['branch']['active']) &&  count($_SESSION['my_user']['branch']['active']) > 1)
		{
			return "public";
		}
		if(isset($_SESSION['supervisor']))
		{
			return "public";
		}
		return "olny from some branch";
	}

	public function usersid()
	{
		if(isset($_SESSION['my_user']['id']))
		{
			return $_SESSION['my_user']['id'];
		}
		else
		{
			return false;
		}
	}

	public function teacher_form()
	{
		if(isset($_SESSION['my_user']['type']) && $_SESSION['my_user']['type'] == "teacher")
		{
			return "public";
		}
		else
		{
			return "jost for teacher load this menu";
		}
	}

	public function supervisor()
	{
		if(global_cls::supervisor())
		{
			return "public";
		}
		return "only supervisor can see this menu";
	}


	public function public_menu()
	{
		return  array(
			array('href' => '', 'title' => gettext('home page')),
			array('href' => 'https://'.DOMAIN.'/portal/login', 'title' => gettext('register')),
			array('href' => 'https://'.DOMAIN.'/portal/login', 'title' => gettext('login')),
			array('href' => 'posts/more', 'title' => gettext('news')),
		 	// array('href' => 'graduate/', 'title' => 'دانش آموختگان'),
			array('href' => 'strategy', 'title' => gettext('strategy')),
		 	// array('href' => 'about', 'title' => 'درباره ما'),
			array('href' => 'contact', 'title' => gettext('contact')),
			);
	}
}
?>