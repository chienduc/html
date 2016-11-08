<?php
class AuthImage {
	var $length;
	var $background;
	var $code;
	function AuthImage($length=6) {
		if(!session_id()) session_start();
		$this->length=$length;
		$this->background=ROOT_PATH."plugins/authimg/backgrounds/pine.gif";
		if(isset($_SESSION['rand_code'])) {
			$this->code = $_SESSION['rand_code'];
		} else {
			$_SESSION['rand_code'] = $this->generateCode($this->length);
			$this->code = $_SESSION['rand_code'];
		}
	}

	function renewCode() {
		$_SESSION['rand_code'] = $this->generateCode($this->length);
		$this->code = $_SESSION['rand_code'];
	}

	function showImage() {
		header("Content-type: image/gif");
		$image = @imagecreatefromgif($this->background) or die("Cannot Initialize new GD image stream");
		$textColor = imageColorAllocate($image, 0x00, 0x00, 0x00);
		ImageString($image, 5, 7, 2, $this->code, $textColor);
		ImageGif($image);
		ImageDestroy($image);
	}

	function generateCode() {
		$str = "";
		for ($i = 0; $i < $this->length; $i++) {
			$a = rand(1,3);
			switch($a) {
				case 1:
					// this numbers refer to numbers of the ascii table (small-caps)
					$str .= chr(rand(97, 122));
					break;
				case 2:
					// this numbers refer to numbers of the ascii table (upper-caps)
					$str .= chr(rand(65, 90));
					break;
				case 3:
					// number
					$str .= rand(1,9);
					break;
			}
		 }
		return $str;
	}	
}
?>
