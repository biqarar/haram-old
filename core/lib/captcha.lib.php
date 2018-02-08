<?php 
/**
* @author baravak
*/
class captcha_lib {
	
	function __construct($cid = false) {
		if(!isset($_SESSION['load_captcha']) || !$_SESSION['load_captcha']){
			page_lib::page('captcha generator not found');
		}
		// if(!preg_match("/^\d{5}$/", $cid)){
		// 	page_lib::page('captcha cid error');
		// }
		$width = 200;
		$height = 80;
		$my_img = imagecreatetruecolor( $width, $height );
		$font = "./static/fonts/BYekan.ttf";
		$background = imagecolorallocate( $my_img, 192, 227, 236 );
		imagefill($my_img, 0, 0, $background);
		$session = '';
		for ($i=0; $i <= 4; $i++) { 
			$number = rand(0,9);
			$session .= $number;
			$text_colour = imagecolorallocate( $my_img, rand(0, 170), rand(0, 210), rand(0, 210) );

			imagettftext( $my_img, rand(16,25), rand(-30, 30), rand(($i*40)+5, ($i+1)*35), rand(15,60), $text_colour, $font, $number );
		}
		$_SESSION['CAPTCHA_GNA'] = $session;
		for ($i=0; $i <= ($width + $height) * 3; $i++) { 
			$text_colour = imagecolorallocate( $my_img, rand(0, 170), rand(0, 210), rand(0, 210) );
			imagesetpixel ( $my_img , rand(0, $width) , rand(0, $height) , $text_colour );
		}

		header( "Content-type: image/png" );

		imagepng( $my_img );
		imagedestroy($my_img);
		exit();
	}
}
?>