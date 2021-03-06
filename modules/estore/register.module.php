<?php
/*************************************************************************
Estore register module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                  
**************************************************************************/
include_once(ROOT_PATH."classes/dao/provinces.class.php");
include_once(ROOT_PATH.'classes/dao/emails.class.php');

$templateFile = "register.tpl.html";
$provinces = new Provinces();
$emails = new Emails($sId);
$plus = $request->element('plus');

# Get Register
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$messages["result_code"][$result_code]);
# At the beginning
$provincesList = $provinces->createComboBox();
if($provincesList) $template->assign('provincesList',$provincesList);
		
$infoClass = "infoText";
$template->assign("infoClass",$infoClass);

if($_POST){	
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		$template->assign("infoClass","error");
		$template->assign('note',$messages["register_error"]);
		$listProvince = $provinces->createComboBox($request->element('province'), 'id', 'name');
		$template->assign('listProvince',$listProvince);
	} else { # Valid data input
		# check duplicate customer username
			if($customers->checkDuplicate($request->element('username'),'username')) {
				$validate['INPUT']['username']['message'] = $messages['username_aready'];
				$validate['INPUT']['username']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
		# check duplicate customer email
			if($customers->checkDuplicate($request->element('email'),'email')) {
				$validate['INPUT']['email']['message'] = $messages['email_aready'];
				$validate['INPUT']['email']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
		$template->assign('error',$validate);	
		$template->assign("infoClass","error");
		$template->assign('note',$messages["register_error"]);
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			# Password
			$pass = $request->element('password','');
			$password = md5($pass);
			#Property 
			$properties = array('');
			$properties = array('password' => $pass,
								'province' => $request->element('province'),
								'cell' => $request->element('cell'));
			# End property
			if($request->element('type') == 1) $status = 1;
			elseif($request->element('type') == 2) $status = 0;
			$data = array('store_id' => $storeId,
						  'area_id'  => $request->element('area'),
						  'areah'  => $request->element('areas'),
						  'type'	=> $request->element('type',1),
						  'username' => $request->element('username'),
						  'password' => $password,
						  'fullname' => Filter($request->element('fullname')),
						  'address' => Filter($request->element('address')),
						  'email' => Filter($request->element('email')),
						  'tel' => Filter($request->element('tel')),
						  'properties' => serialize($properties),
						  'date_created' => date("Y-m-d H:i:s"),
						  'status' => $status);
			$newId = $customers->addData($data);
			
			header('location:/index.php?op=estore&act=register&rcode=1');
		}
	}
}

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['register'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

$pageTitle = $messages['register'];

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['username'] = $validate->validUsername($request->element('username'));
	$error['INPUT']['password'] = $validate->validPassword($request->element('password'));
	$error['INPUT']['retype_pass'] = $validate->validPassword($request->element('retype_pass'),$messages['confirm']);
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'),$messages['fullname']);
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'));
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$messages['address']);
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'),$messages['tel']);
	$error['INPUT']['cell'] = $validate->validString($request->element('cell'),$messages['cell']);
	$error['INPUT']['type'] = $validate->pasteString($request->element('type'));

	if($error['INPUT']['username']['error'] || $error['INPUT']['password']['error'] || $error['INPUT']['retype_pass']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['email']['error'] || $error['INPUT']['cell']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;


# Page title, keywords, description
$pageTitle = $messages['register'];
}
?>