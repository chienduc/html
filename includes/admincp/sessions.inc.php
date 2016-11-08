<?php
/*************************************************************************
Admin Sessions manager
----------------------------------------------------------------
Bido Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 30/06/2011
Author: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
error_reporting(9);
if (!defined( 'ROOT_PATH' )) {
	define('ROOT_PATH', dirname(__FILE__).'/');
}
session_start();

if($op != 'invalidurl') {
	// check estore 
	#Get Store Info
	$storeId = 1;
	if($sCode) {
		$stores = new EStores();
		$storeId = $stores->getStoreId("`subdomain`='$sCode' OR `domain`='$sCode'");
		if(!$storeId) die('Invalid store ID.');
		$estore = $stores->getObject($storeId);
		$template->assign('sCode',$sCode);
		if($estore) $template->assign('estore',$estore);
	}

	// check user login
	if(isset($_SESSION['userId']) && $_SESSION['userId']) {
		$userId = $_SESSION['userId'];
		$users = new Users(0);
		$trackings = new Trackings(0);
		$userInfo = $users->getObject($userId,'id');
		if($userInfo) {
			$template->assign('authUser',$userInfo);
			$_SESSION['storeId'] = 0;
			//$storeId = 0;
		} else {
			$_SESSION['userId'] = 0;
			$op = 'login';
		}
	} else {
		$_SESSION['userId'] = 0;
		$op = 'login';
	}
}

?>