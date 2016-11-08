<?php
/*************************************************************************
Login module
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd
Email: info@derasoft.com                                    
Last updated: 02/07/2008
**************************************************************************/
include_once(ROOT_PATH."classes/data/validate.class.php");

$store_users = new StoreUsers($sId);
$templateFile = "lostpass.tpl.html";

# support
include_once(ROOT_PATH.'classes/dao/support.class.php');
$support = new Support();
$supportItems = $support->getObjects(1,"status=1", array('position'=>'ASC'));
$template->assign("supportItems",$supportItems);
include_once(ROOT_PATH.'classes/dao/products.class.php');
$products = new Products($sId);
$template->assign('products',$products);

$email = strtolower($request->element("email",""));
$username = $request->element("username","");
$tel = $request->element("tel","");
$code = $request->element("code","");
$plus = $request->element("plus","");
$act = $request->element("act","");
$time = time();
if($_POST){
	$listerror = validateData($request);
	$check = checkvalidate($listerror);
	if($check == 0){
		$isUsername = $store_users->checkDuplicate($username,'username');
		$isTel = $store_users->checkDuplicate($tel,'tel');
		$isEmail = $store_users->checkDuplicate($email,'email');

		if (($isTel == 1) and ($isUsername == 1) and ($isEmail == 1)){
			$update = $store_users->updateData(array('properties' => $time),$username,'username');
			$url = "http://"."$subDomain".".".DOMAIN."/index.php?op=estore&act=lostpass&plus=changepass&addon=$username";
			$url1 = "http://"."$subDomain".".".DOMAIN."/lostpass/changepass/$username".".html";

			# Send mail
			$To          = strip_tags($email);
			$from   = 'quang.ta@derasoft.com';
			$Subject     =$messages["newpass_email_title"];
			$body = sprintf($messages["newpass_email_body"],$url1,$url1,$url,$url);
			$header = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\nReply-to: $from";
			$ok = mail($To,$Subject,$body,$header);
			unset($_SESSION['rand_code']);
			$infoText = $messages['newpass_email_success'];
			$infoClass = "infoText";
			$template->assign("infoText",$infoText);
			$template->assign("ok",$ok);
			$template->assign("infoClass",$infoClass);
		}else{
			$infoClass = 'error';
			$template->assign("infoText",$messages['newpass_email_error']);
			$template->assign("infoClass",$infoClass);
			$template->assign("username",$username);
			$template->assign("email",$email);
			$template->assign("tel",$tel);
			$template->assign("code",$code);
		}
	}else{
		$infoClass = "error";
		$template->assign("infoText",$messages['newpass_error']);
		$template->assign("infoClass",$infoClass);
		$template->assign("username",$username);
		$template->assign("email",$email);
		$template->assign("tel",$tel);
		$template->assign("code",$code);
		$template->assign('listerror',$listerror);
	}
}

function checkvalidate($validate){
	foreach($validate as $check){
		if($check != NULL)
			return 1;
	}
	return 0;
}

function validateData($request) {
	global $messages;
	$error = array();
	$validate = new Validate();
	$error['email'] = $validate->validEmail($messages['email'],$request->element('email'));
	$error['username'] = $validate->validString($messages['username'],$request->element('username'));
	$error['tel'] = $validate->validString($messages['tel'],$request->element('tel'));
	if(strtolower($request->element('code'))!= strtolower($_SESSION['rand_code'])) $error['code'] = $messages['invalid_security_code'];
	else $error['code'] = NULL;
	return $error;
}


if ($plus == 'changepass'){
	$templateFile = "changepass.tpl.html";
	$template->assign('plus',$plus);
	$template->assign('act',$act);
	$addon = $request->element('addon','');
	$template->assign('addon',$addon);

	$account = $store_users->getObject($addon,'username');
	if ($account)
		$time = $account->getProperties();

	$pass = $request->element('password','');
	$password = md5($pass);

	function validateChangePassword($request) {
		global $messages;
		$error = array();
		$validate = new Validate();
		$error['password'] = $validate->validPassword($messages['password'],$request->element('password'));
		$error['cpassword'] = $validate->validTestPass($messages['confirm'],$request->element('password'),$request->element('cpassword'));
		return $error;
	}

	if ($_POST){
		$listerrorChangepass = validateChangePassword($request);
		$check = checkvalidate($listerrorChangepass);
		if($check == 0){
			echo $isUsername = $store_users->checkDuplicate($addon,'username');
			if ($isUsername == 1){
				$changepass = $store_users->updateData(array('password' => $password),$addon,'username');
				$infoText = $messages['changepass_email_success'];
				$infoClass = "infoText";
				$template->assign("infoText",$infoText);
				$template->assign("infoClass",$infoClass);
				$templateFile = "login.tpl.html";
			}else{
				$infoClass = 'error';
				$template->assign("infoText",$messages['changepass_email_error']);
				$template->assign("infoClass",$infoClass);
			}
		}else{
			$infoClass = "error";
			$template->assign("infoText",$messages['changepass_error']);
			$template->assign("infoClass",$infoClass);
			$template->assign('listerrorChangepass',$listerrorChangepass);
		}
	}
}
?>