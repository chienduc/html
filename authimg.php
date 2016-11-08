<?php
/*************************************************************************
Authimage
----------------------------------------------------------------
Vnnavi 2008 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 31/07/2008
Coder: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
if (!defined( "ROOT_PATH" )) {
	define("ROOT_PATH", dirname(__FILE__)."/");
}
session_start();
include_once(ROOT_PATH."plugins/authimg/authimg.php");
$authimg = new AuthImage();
$op=isset($_REQUEST['op'])?$_REQUEST['op']:'showImage';
if($op=="renewCode") $authimg->renewCode();
$authimg->showImage();

?>