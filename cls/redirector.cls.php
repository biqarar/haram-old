<?php
class redirector_cls{
	private $exit = true, $aurl, $surl, $url = false;
	function __construct($redirect = false, $php = false){
		// $this->exit = $exit;
		$this->php = $php;
		$this->surl = config_lib::$surl;
		$this->aurl = config_lib::$aurl;
		if(is_string($redirect)){
			$this->url = preg_replace("/^\//", '', $redirect);
		}
	}

	public function urlChange($str = false, $replace = ''){
		if($str === false){
			$url = true;
			$this->aurl = array();
			$this->surl = array();
		}elseif(is_int($str)){
			$url = (isset($this->aurl[$str]))? $str : count($this->aurl);
		}elseif(is_string($str)){
			$url = (isset($this->surl[$str]))? $str : false;
			if($url){
				$url = array_search($url.'='.$this->surl[$str], $this->aurl);
			}else{
				$url = count($this->aurl);
			}
		}
		if(!is_bool($url)){
			if(is_int($str)){
				$this->aurl[$url] = $replace;
			}elseif(is_string($str)){
				$this->surl[$str] = $replace;
				$this->aurl[$url] = $str.'='.$replace;
			}
		}
		return $this;
	}

	public function compileUrl(){
		if($this->url === false){
			$redirect = join($this->aurl, '/');
		}else{
			$redirect = $this->url;
		}
		return $redirect;
	}
	public function redirect(){
		$redirect = $this->compileUrl();
		$redirect = host.'/'.$redirect;
		if($this->php){
			header("Location:$redirect");
		}else{
			echo '<html><head>';
			echo '<meta http-equiv="refresh" content="3; URL='.$redirect.'">';
			echo "<meta charset='utf-8'>";
			echo '</head></body>';
			echo '<h1>مرکز قرآن و حدیث کریمه اهل بیت علیها السلام</h1><hr />';
			echo 'Redirect to '.$redirect.'...';
			echo '</body></html>';

		}
		if($this->exit){
			exit();
		}
	}
}
?>