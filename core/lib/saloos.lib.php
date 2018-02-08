<?php
namespace lib;
/**
 * saloos main configure
 * @author baravak <itb.baravak@gmail.com>
 */
class saloos{
	/**
	 * @var string strig of core version
	 */
	const version = "0.9.2";
	/**
	 * @var float float of core version
	 */
	const iversion = 0.92;

	/**
	 * constractor
	 */
	
	public function __construct(){
		if(!defined("helper")){
			define("helper", lib.'../helper/');
		}
		if(!defined("slshelper")){
			define("slshelper", lib.'../helper/');
		}
	}

	public static function helper(){
		return new \lib\saloos\helper();
	}
}
?>