<?php
include_once(ROOT_PATH."classes/data/validate.class.php");
include_once(ROOT_PATH."classes/dao/provinces.class.php");
$provinces = new Provinces();
if(isset($_SESSION['store_customerId'])) $uId = (int)$_SESSION['store_customerId'];
if(isset($uId)) $customerInfo = $customers->getObject($uId,'id');
if($customerInfo) $templateFile = "profile.tpl.html";
else $templateFile = "login.tpl.html";
if($customerInfo){
	$listProvince = $provinces->createComboBox($customerInfo->getProperty('province'), 'id', 'name');
	$template->assign('listProvince',$listProvince);	
	$template->assign('item',$customerInfo);
	if($_POST){
		if($customerInfo->getType() == 1){
			$validate = validateData($request);
			if($validate['invalid']) {	# data input is not in valid form
							$template->assign('error',$validate);
							$template->assign('note',$messages['item_edit_error']);
							$template->assign('infoClass',"error");
			} else { # Valid data input
					# Check new password and confirm password
					if($request->element('password')) {
						$new_password = md5($request->element('password'));
						$confirm_password = md5($request->element('confirm_password'));
						if($new_password != $confirm_password) { # New password is same as confirm password
							$validate['INPUT']['confirm_password']['message'] = $messages['invalid_confirm_password'];
							$validate['INPUT']['confirm_password']['error'] = 1;
							$validate['invalid'] = 1;
							$template->assign('error',$validate);
							$template->assign('infoClass',"error");
						}
					}		
					# check duplicate email
					if($customers->checkDuplicate($request->element('email'),'email',"`id` <>'$uId'")) {
						$validate['INPUT']['email']['message'] = $amessages['email_duplicated'];
						$validate['INPUT']['email']['error'] = 1;
						$validate['invalid'] = 1;
						$template->assign('error',$validate);
					}
					# Everything is ok. Update data to DB
					if(!$validate['invalid']) {
						$template->assign('note',$messages['update_profile_ok']);
						$infoClass = "infoText";
						$template->assign('infoClass',$infoClass);
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
									  'fullname' => Filter($request->element('fullname')),
									  'address' => Filter($request->element('address')),
									  'email' => Filter($request->element('email')),
									  'tel' => Filter($request->element('tel')),
									  'properties' => serialize($properties),
									  'date_created' => date("Y-m-d H:i:s"),
									  'status' => 1);
							if($request->element('password')) $data['password'] = md5($request->element('password'));
							$customers->updateData($data,$uId);
							$template->assign('item',$customerInfo);
							unset($_SESSION['rand_code']);
							header('location:'.$_SERVER['HTTP_REFERER']);
					}
			}
			
		}else{
			$validate = validateData2($request);
			if($validate['invalid']) {	# data input is not in valid form
					$template->assign('error',$validate);
					$template->assign('note',$messages['item_edit_error']);
					$template->assign('infoClass',"error");
			} else { # Valid data input
				# Check new password and confirm password
				if($request->element('password')) {
					$new_password = md5($request->element('password'));
					$confirm_password = md5($request->element('confirm_password'));
					if($new_password != $confirm_password) { # New password is same as confirm password
						$validate['INPUT']['confirm_password']['message'] = $messages['invalid_confirm_password'];
						$validate['INPUT']['confirm_password']['error'] = 1;
						$validate['invalid'] = 1;
						$template->assign('error',$validate);
						$template->assign('infoClass',"error");
					}
				}		
				# check duplicate email
				if($customers->checkDuplicate($request->element('email'),'email',"`id` <>'$uId'")) {
					$validate['INPUT']['email']['message'] = $messages['email_duplicated'];
					$validate['INPUT']['email']['error'] = 1;
					$validate['invalid'] = 1;
					$template->assign('error',$validate);
				}
				# Everything is ok. Update data to DB
				if(!$validate['invalid']) {
				$template->assign('note',$messages['update_profile_ok']);
				$infoClass = "infoText";
				$template->assign('infoClass',$infoClass);
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
								  'fullname' => Filter($request->element('fullname')),
								  'address' => Filter($request->element('address')),
								  'email' => Filter($request->element('email')),
								  'tel' => Filter($request->element('tel')),
								  'properties' => serialize($properties),
								  'date_created' => date("Y-m-d H:i:s"),
								  'status' => '');
						if($request->element('password')) $data['password'] = md5($request->element('password'));
						$customers->updateData($data,$uId);
						$template->assign('item',$customerInfo);
						unset($_SESSION['rand_code']);
						header('location:'.$_SERVER['HTTP_REFERER']);
				}
			}
				
		}
	}
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	if($request->element('password')) $error['INPUT']['password'] = $validate->validPassword($request->element('password'));
	else $error['INPUT']['password'] = $validate->pasteString($request->element('password'));
	if($request->element('retype_pass')) $error['INPUT']['retype_pass'] = $validate->validPassword($request->element('retype_pass'),$messages['confirm']);
	else $error['INPUT']['retype_pass'] = $validate->pasteString($request->element('retype_pass'));
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'),$messages['fullname']);
	$error['INPUT']['date_born'] = $validate->validString($request->element('date_born'),$messages['date_born']);
	$error['INPUT']['sex'] = $validate->pasteString($request->element('sex'));
	
	$error['INPUT']['name_contact'] = $validate->validString($request->element('name_contact'),$messages['name_contact']);
	$error['INPUT']['address_contact'] = $validate->validString($request->element('address_contact'),$messages['address_contact']);
	$error['INPUT']['tel_contact'] = $validate->validString($request->element('tel_contact'),$messages['tel_contact']);
	$error['INPUT']['email_contact'] = $validate->validString($request->element('email_contact'),$messages['email_contact']);
	
	$code = strtolower($request->element('code'));
	$error['INPUT']['code'] = $validate->validCode($code,$messages['security']);
	if($error['INPUT']['fullname']['error'] || $error['INPUT']['date_born']['error'] || $error['INPUT']['name_contact']['error'] || $error['INPUT']['address_contact']['error'] || $error['INPUT']['tel_contact']['error'] || $error['INPUT']['email_contact']['error'] || $error['INPUT']['code']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData2($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	if($request->element('password')) $error['INPUT']['password'] = $validate->validPassword($request->element('password'));
	else $error['INPUT']['password'] = $validate->pasteString($request->element('password'));
	if($request->element('retype_pass')) $error['INPUT']['retype_pass'] = $validate->validPassword($request->element('retype_pass'),$messages['confirm']);
	else $error['INPUT']['retype_pass'] = $validate->pasteString($request->element('retype_pass'));
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'),$messages['fullname']);
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$messages['address']);
	$error['INPUT']['tel'] = $validate->pasteString($request->element('tel'),$messages['tel']);
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'));
	$error['INPUT']['website_company'] = $validate->pasteString($request->element('website_company'));
	$error['INPUT']['about_company'] = $validate->pasteString($request->element('about_company'));
	
	$error['INPUT']['name_contact'] = $validate->validString($request->element('name_contact'),$messages['name_contact']);
	$error['INPUT']['address_contact'] = $validate->validString($request->element('address_contact'),$messages['address_contact']);
	$error['INPUT']['tel_contact'] = $validate->validString($request->element('tel_contact'),$messages['tel_contact']);
	$error['INPUT']['email_contact'] = $validate->validString($request->element('email_contact'),$messages['email_contact']);
	
	$code = strtolower($request->element('code'));
	$error['INPUT']['code'] = $validate->validCode($code,$messages['security']);
	if($error['INPUT']['fullname']['error'] || $error['INPUT']['address']['error'] || $error['INPUT']['name_contact']['error'] || $error['INPUT']['address_contact']['error'] || $error['INPUT']['tel_contact']['error'] || $error['INPUT']['email_contact']['error'] || $error['INPUT']['code']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' =>  $messages['profile'], '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
$pageTitle = $messages['profile'];
?>