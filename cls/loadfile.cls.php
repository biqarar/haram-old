<?php 
class loadfile_cls {
	public function __construct($x) {
		header( "Content-type: image/png" );
		$y =  '/project/qhkarimeh/updfile/' . $x;
		echo $y;
		imagepng( $y );
		imagedestroy($y);
		var_dump($x);exit();
	}
}


 ?>