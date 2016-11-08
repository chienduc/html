<?php
// ImageCapture for Formula Math plugins for CKEditor
// Mai Minh 02 March 2012

if($_POST["save"]){
	$type = $_POST["type"];
	if($_POST["name"] and ($type=="JPG" or $type=="PNG")){
		$img = base64_decode($_POST["image"]);

		$myFile = "../../upload/fmath/".$_POST["name"].".".$type ;
		$fh = fopen($myFile, 'w');
		fwrite($fh, $img);
		fclose($fh);
		echo "../../upload/fmath/".$_POST["name"].".".$type;
	}
}else{
	header('Content-Type: image/jpeg');
	echo base64_decode($_POST["image"]);
}
?>