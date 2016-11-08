<?php
/*************************************************************************
Login module
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
**************************************************************************/
$templateFile = "login.tpl.html";
$error ='';
$infoClass = "infoText";
$template->assign('infoClass',$infoClass);


if($_POST) {
	$username = trim($request->element("username"));
	$password = trim($request->element("password"));

	
	$userId = $customers->authenticateUser($username,$password);
	if($userId) {
		$_SESSION['store_customerId'] = $userId;
		if ($plus == 'loginOrder'){
			header("location: ".$_SERVER['HTTP_REFERER']);
		}else{
				header("location:/");
		}
	} else {
			$_SESSION['store_customerId'] = 0;
			$error = $messages['login_error'];
			$infoClass = 'error';
			$template->assign('infoClass',$infoClass);
	}
	$template->assign('error',$error);	
}
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['login'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);
# Page title, keywords, description
$pageTitle = $messages['login'];
?>