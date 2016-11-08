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
if($plus == 'ntd')	$templateFile = "nha-tuyen-dung.tpl.html";
elseif($plus == 'ntv')	$templateFile = "nguoi-tim-viec.tpl.html";
if($plus)	$template->assign('plus',$plus);
# Get Register
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$messages["result_code"][$result_code]);
# At the beginning
$provincesList = $provinces->createComboBox();
if($provincesList) $template->assign('provincesList',$provincesList);
		
$infoClass = "infoText";
$template->assign("infoClass",$infoClass);

if ($plus == 'apply'){ 
	$pageTitle = $messages['active_profile'];
	$templateFile = "login.tpl.html";
	$cId = $request->element('id');
	$customerInfo = $customers->getObject($cId,'id');
	if ($customerInfo){
		$customers->changeStatus($cId ,1);
	}
	# Generate the navigation bar
	$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
	$navigationItems[] = array('name' => $messages['active_profile'], 'url' => '', 'current' => '1');
	$template->assign('navigationItems',$navigationItems);

}else{
if($_POST){	
	switch($plus) {
		case 'ntd':
			$gallery_path = ROOT_PATH."upload/$storeId/resources/";
			# Validate the data input
			$validate = validateData($request);
			if($validate['invalid']) {	# data input is not in valid form
				$template->assign('error',$validate);	
				$template->assign("infoClass","error");
				$template->assign('note',$messages["register_error"]);
			} else { # Valid data input
				# check duplicate customer username
					if($customers->checkDuplicate($request->element('username'),'username',"`type` = 2")) {
						$validate['INPUT']['username']['message'] = $messages['username_aready'];
						$validate['INPUT']['username']['error'] = 1;
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
					# Files upload
					$files = isset($_FILES['logo'])?$_FILES['logo']:'';
					if($files) {
						$img = addslashes(Filter(rand()."_".$files['name']));
						$tmp_img = $files['tmp_name'];
						$size = $files['size'];
						$type=strtolower(substr($img,-3));
						if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
							# Upload
							if(isImage($img)) {
							move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
							resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
							resize($gallery_path,$gallery_path,'l_'.$img,'t_'.$img,DEFAULT_THUMBNAIL_SIZE,DEFAULT_THUMBNAIL_SQUARE,DEFAULT_PHOTO_QUALITY);
							$logo = $img;
							} elseif(isVideo($img)) {
								move_uploaded_file($tmp_img,$gallery_path.$img);
								$logo = $img;
							} else {
								move_uploaded_file($tmp_img,$gallery_path.$img);
								$logo = $img;
							}
						} #/if (preg_match
					}
					$properties = array('password' => $pass,
										'website_company' => $request->element('website_company'),
										'about_company' => $request->element('about_company'),
										'name_contact' => $request->element('name_contact'),
										'address_contact' => $request->element('address_contact'),
										'tel_contact' => $request->element('tel_contact'),
										'email_contact' => $request->element('email_contact'),
										'logo' => $logo);
					# End property
					$data = array('store_id' => $storeId,
								  'area_id'  => $request->element('province'),
								  'type'	=> 2,
								  'username' => $request->element('username'),
								  'password' => $password,
								  'fullname' => Filter($request->element('fullname')),
								  'address' => Filter($request->element('address')),
								  'email' => Filter($request->element('email')),
								  'tel' => Filter($request->element('tel')),
								  'properties' => serialize($properties),
								  'date_created' => date("Y-m-d H:i:s"),
								  'status' => '');
					$newId = $customers->addData($data);
					$emailItem = $emails->getObject('REGISTRATION_EMAIL_NTD','name',"status = 1");
					if($emailItem){
						$tokens = explode(',',$emailItem->getTokens());
						$bodyEmail = $emailItem->getContent();
						$subjectEmail = $emailItem->getTitle();
						foreach($tokens as $token){
							$subjectEmail = str_replace("{".$token."}",'%s',$subjectEmail);
							$bodyEmail = str_replace("{".$token."}",'%s',$bodyEmail); 
						}
					}else{
						$subjectEmail = $messages["register_email_ntd_title"];
						$bodyEmail = $messages["register_email_ntd_body"];
					}
					$To = $request->element('email');
					$url = DOMAIN."/register/apply-".$newId.".html";
					$Subject     =sprintf($subjectEmail,DOMAIN);
					$from=$estore->getEmail();
					$Body = sprintf($bodyEmail,$request->element('fullname'),DOMAIN,$request->element('username'),$pass,$url,DOMAIN,DOMAIN);
					$header = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\nReply-to: $from";
					$ok = mail($To,$Subject,$Body,$header);
					unset($_SESSION['rand_code']);
					header('location:/index.php?op=estore&act=register-user&plus=ntd&rcode=2');
				}
			}
		break;
		case 'ntv':
			$gallery_path = ROOT_PATH."upload/$storeId/resources/";
			# Validate the data input
			$validate = validateDataUser($request);
			if($validate['invalid']) {	# data input is not in valid form
				$template->assign('error',$validate);	
				$template->assign("infoClass","error");
				$template->assign('note',$messages["register_error"]);
			} else { # Valid data input
				# check duplicate customer username
					if($customers->checkDuplicate($request->element('username'),'username',"`type` = 2")) {
						$validate['INPUT']['username']['message'] = $messages['username_aready'];
						$validate['INPUT']['username']['error'] = 1;
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
					# Files upload
					$files = isset($_FILES['avatar'])?$_FILES['avatar']:'';
					if($files) {
						$img = addslashes(Filter(rand()."_".$files['name']));
						$tmp_img = $files['tmp_name'];
						$size = $files['size'];
						$type=strtolower(substr($img,-3));
						if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
							# Upload
							if(isImage($img)) {
							move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
							resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
							resize($gallery_path,$gallery_path,'l_'.$img,'t_'.$img,DEFAULT_THUMBNAIL_SIZE,DEFAULT_THUMBNAIL_SQUARE,DEFAULT_PHOTO_QUALITY);
							$logo = $img;
							} elseif(isVideo($img)) {
								move_uploaded_file($tmp_img,$gallery_path.$img);
								$logo = $img;
							} else {
								move_uploaded_file($tmp_img,$gallery_path.$img);
								$logo = $img;
							}
						} #/if (preg_match
					}
					$properties = array('password' => $pass,
										'date_born' => $request->element('date_born'),
										'sex' => $request->element('sex'),
										'name_contact' => $request->element('name_contact'),
										'address_contact' => $request->element('address_contact'),
										'tel_contact' => $request->element('tel_contact'),
										'email_contact' => $request->element('email_contact'),
										'avatar' => $avatar);
					# End property
					$data = array('store_id' => $storeId,
								  'area_id'  => $request->element('province'),
								  'type'	=> 1,
								  'username' => $request->element('username'),
								  'password' => $password,
								  'fullname' => Filter($request->element('fullname')),
								  'address' => Filter($request->element('address')),
								  'email' => Filter($request->element('email')),
								  'tel' => Filter($request->element('tel')),
								  'properties' => serialize($properties),
								  'date_created' => date("Y-m-d H:i:s"),
								  'status' => 1);
					$newId = $customers->addData($data);
					$emailItem = $emails->getObject('REGISTRATION_EMAIL_NTD','name',"status = 1");
					if($emailItem){
						$tokens = explode(',',$emailItem->getTokens());
						$bodyEmail = $emailItem->getContent();
						$subjectEmail = $emailItem->getTitle();
						foreach($tokens as $token){
							$subjectEmail = str_replace("{".$token."}",'%s',$subjectEmail);
							$bodyEmail = str_replace("{".$token."}",'%s',$bodyEmail); 
						}
					}else{
						$subjectEmail = $messages["register_email_ntv_title"];
						$bodyEmail = $messages["register_email_ntv_body"];
					}
					$To = $request->element('email');
					$url = DOMAIN."/register/apply-".$newId.".html";
					$Subject     =sprintf($subjectEmail,DOMAIN);
					$from=$estore->getEmail();
					$Body = sprintf($bodyEmail,$request->element('fullname'),DOMAIN,$request->element('username'),$pass,$url,DOMAIN,DOMAIN);
					$header = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\nReply-to: $from";
					$ok = mail($To,$Subject,$Body,$header);
					unset($_SESSION['rand_code']);
					header('location:/index.php?op=estore&act=register-user&plus=ntd&rcode=2');
				}
			}
		break;
	}
	
}

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['register'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);
}

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
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$messages['address']);
	$error['INPUT']['tel'] = $validate->pasteString($request->element('tel'),$messages['tel']);
	$error['INPUT']['email'] = $validate->pasteString($request->element('email'));
	$error['INPUT']['website_company'] = $validate->pasteString($request->element('website_company'));
	$error['INPUT']['about_company'] = $validate->pasteString($request->element('about_company'));
	
	$error['INPUT']['name_contact'] = $validate->validString($request->element('name_contact'),$messages['name_contact']);
	$error['INPUT']['address_contact'] = $validate->validString($request->element('address_contact'),$messages['address_contact']);
	$error['INPUT']['tel_contact'] = $validate->validString($request->element('tel_contact'),$messages['tel_contact']);
	$error['INPUT']['email_contact'] = $validate->validEmail($request->element('email_contact'));
	
	$code = strtolower($request->element('code'));
	$error['INPUT']['code'] = $validate->validCode($code,$messages['security']);
	if($error['INPUT']['username']['error'] || $error['INPUT']['password']['error'] || $error['INPUT']['retype_pass']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['address']['error'] || $error['INPUT']['name_contact']['error'] || $error['INPUT']['address_contact']['error'] || $error['INPUT']['tel_contact']['error'] || $error['INPUT']['email_contact']['error'] || $error['INPUT']['code']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateDataUser($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['username'] = $validate->validUsername($request->element('username'));
	$error['INPUT']['password'] = $validate->validPassword($request->element('password'));
	$error['INPUT']['retype_pass'] = $validate->validPassword($request->element('retype_pass'),$messages['confirm']);
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'),$messages['fullname']);
	$error['INPUT']['date_born'] = $validate->validString($request->element('date_born'),$messages['date_born']);
	$error['INPUT']['sex'] = $validate->pasteString($request->element('sex'));
	
	$error['INPUT']['name_contact'] = $validate->validString($request->element('name_contact'),$messages['name_contact']);
	$error['INPUT']['address_contact'] = $validate->validString($request->element('address_contact'),$messages['address_contact']);
	$error['INPUT']['tel_contact'] = $validate->validString($request->element('tel_contact'),$messages['tel_contact']);
	$error['INPUT']['email_contact'] = $validate->validEmail($request->element('email_contact'),$messages['email_contact']);
	
	$code = strtolower($request->element('code'));
	$error['INPUT']['code'] = $validate->validCode($code,$messages['security']);
	if($error['INPUT']['username']['error'] || $error['INPUT']['password']['error'] || $error['INPUT']['retype_pass']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['date_born']['error'] || $error['INPUT']['name_contact']['error'] || $error['INPUT']['address_contact']['error'] || $error['INPUT']['tel_contact']['error'] || $error['INPUT']['email_contact']['error'] || $error['INPUT']['code']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
# Page title, keywords, description
$pageTitle = $messages['register'];
?>