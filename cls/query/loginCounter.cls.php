<?php
class query_loginCounter_cls extends query_cls
{
	
	public function config() {

	}

	public function check(){

		if(isset($_SESSION['load_captcha']) AND
			 isset($_SESSION['CAPTCHA_GNA']) AND 
			 $_SESSION['CAPTCHA_GNA'] != post::captcha()){
			
			debug_lib::fatal("اعداد درون تصویر را به صورت صحیح وارد کنید");
			
			return false;

		}else{
						
			return true;
		}
	}

	public function counter($type = "login", $cCount = 3, $cTime = 300) {

		if(!$this->check()) return false;

		$sql = $this->sql()->tableLogin_counter();
		$ip = $_SERVER['REMOTE_ADDR'];
		$n = $sql->whereIp($ip)->select();

		if($n->num() == 0) {

			$i = $this->sql()
				->tableLogin_counter()
				->setType($type)
				->setTime(time())
				->setCount(1)
				->setIp($ip)
				->insert();
			return true;
		}else{
			$ret = $n->assoc();
			$time = intval($ret['time']);
			$count = intval($ret['count']);
			if((time() - $time) > $cTime){
				$this->sql()->tableLogin_counter()
					->setTime(time())
					->setCount(1)
					->whereIp($ip)
					->update();
				return true;
			}elseif($count < $cCount) {
				$f = $this->sql()->tableLogin_counter()->setCount($count+1)->whereIp($ip);
				$f->update();
				return true;
			}else{
				$this->load_captcha();
				return true;
			}
		}
	}

	public function load_captcha() {
		$_SESSION['load_captcha'] = true;
	}

	public function unload_captcha() {
		if(isset($_SESSION['load_captcha'])) unset($_SESSION['load_captcha']);
	}	

	public function register() {
		return $this->counter("register", 60, 600);	
	}

	public function clear() {
		$this->sql()->tableLogin_counter()->whereIp($_SERVER['REMOTE_ADDR'])->delete();
		$this->unload_captcha();

	}

	public function login() {
		return $this->counter("login", 3, 300);
	}
}
?>