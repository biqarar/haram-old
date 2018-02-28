<?php
// ------------------------------------------------------- Define SERVERNAME(url) as DOMAIN
define("DOMAIN"		, $_SERVER["SERVER_NAME"]);

// ------------------------------------------------------- SALOOS location
define("core"		, preg_replace("[\\\\]", "/", realpath(__DIR__."/../../saloos/").'/') );

// ------------------------------------------------------- root location
define("root_dir"	, preg_replace("[\\\\]", "/", realpath(__DIR__."/../").'/') );

// ------------------------------------------------------- define classes location
define("cls"		, root_dir."includes/cls/");

// ------------------------------------------------------- define sql locations
define("sql"		, root_dir."includes/sql/");

// ------------------------------------------------------- define main_domain location
$host_names = explode(".", DOMAIN);
$url_raw 	= $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
define("URL_RAW", $url_raw);
define("MAIN_DOMAIN", $url_raw);

// if you want to create a new repository simply copy contenr folder and rename to named like content-test
// then create a folder test in content-test, now you can see the result on sample.com/test
// first: we create the url of your module, then check if that folder exist, show related content automatically
if(count($host_names)==3)
	$repository = root_dir.'content-'.$host_names[0].'/';
else
{
	$tmp = explode("/", $_SERVER['REQUEST_URI']);
	$repository = root_dir.'content-'.next($tmp).'/';
}
// var_dump($repository);
if(!is_dir($repository))

	define("content"	, root_dir."content/");
else
	define("content"	, $repository);

/**
 * Localized Language, defaults to English.
 *
 * Change this to localize Saloos. A corresponding MO file for the chosen
 * language must be installed to content/languages. For example, install
 * fa_IR.mo to content/languages and set LANGUAGE to 'fa_IR' to enable Persian
 * language support.
 */
define('LANGUAGE', 'fa_IR');





/**
We don't need this lines! MOVE IT TO SALOOS
**/

// if DEBUG is true you can see the full error description, if set to false show userfriendly messages
if (DEBUG)
{
	error_reporting(E_ALL);
	ini_set('display_errors'		,'On');
	ini_set('display_startup_errors','On');
	ini_set('error_reporting'		,'E_ALL | E_STRICT');
	ini_set('track_errors'			,'On');
	ini_set('display_errors'		,1);
	error_reporting(E_ALL);
}

// set localization settings
$locale = LANGUAGE;
putenv( "LC_ALL={$locale}" );
setlocale( LC_ALL				, $locale );
$domain = $locale;
bindtextdomain($domain			, root_dir.'languages/');
textdomain("*");

/** Define PATH as the site location */
define("PATH", preg_replace("/index\.php$/", "", $_SERVER["SCRIPT_NAME"]));

define("host", 'http://'.DOMAIN);

header("Content-Type:text/html; charset=UTF-8");
define('FACHR'					, 'ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهیآيئؤكآأإة');
define("lib"					, core."lib/");

session_start();

/**
Unil this line
**/







// if Saloos exist, require it else show related error message
if ( file_exists( core . 'autoload.php') )
	require_once( core . 'autoload.php');
else
{   // A config file doesn't exist
	echo( "<p>We can't find <b>Saloos</b>! Please contact administrator!</p>" );
	exit();
}

//hendel
$main = new main_lib();
 ?>