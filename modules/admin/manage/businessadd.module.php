<?php
/*************************************************************************
Adding business module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Email: info@derasoft.com
Last updated: 09/09/2011
Coder: Tran Thi My Xuyen
**************************************************************************/
$userInfo->checkPermission('business','add');
$templateFile = 'managebusiness.tpl.html';
include_once(ROOT_PATH.'classes/dao/businesss.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/areas.class.php');
$businesss = new Businesss($storeId);
$areas = new Areas(1);
$fields = new Fields($storeId);  
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."businesss/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_business'] => '/'.ADMIN_SCRIPT.'?op=manage&act=business',
				$amessages['add_new'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=business';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => '',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);
# Allow some javascript
$template->assign('ckEditor',1);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

$comboArea=$areas->generateCombo( $request->element('area_id'));
$template->assign('comboArea',$comboArea);
# Get list of custom fields
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='business'",array('position' => 'ASC'));
if($fieldList) $template->assign('fieldList',$fieldList);

if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		
	} else { # Valid data input
		# check duplicate business username
			if($businesss->checkDuplicate($request->element('username'),'username')) {
				$validate['INPUT']['username']['message'] = $amessages['username_duplicated'];
				$validate['INPUT']['username']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
		# check duplicate business email
		
			if($businesss->checkDuplicate($request->element('email'),'email')) {
				$validate['INPUT']['email']['message'] = $amessages['email_duplicated'];
				$validate['INPUT']['email']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
			
				# Check if duplicate slug
		$slug = TextFilter::urlize($request->element('fullname'),false,'-');
		$i = 0;
		$dup = 1;
		while($dup) {
			$dup = $businesss->checkDuplicate($slug.($i?'-'.$i:''),'slug');
			if($dup) $i++;
		}
		$slug .= $i?'-'.$i:'';
		
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			
			# Check if gallery folder is exists
			if(!file_exists($gallery_root)) mkdir("$gallery_root");
			if(!file_exists($gallery_path)) mkdir("$gallery_path");
			# User upload
			 $userUpload = $userInfo->getUsername();
		
			#File Avatar
			$fileAvatr = isset($_FILES['avatar'])?$_FILES['avatar']:'';
			if($fileAvatr) {
				$img = addslashes(Filter(rand()."_".$fileAvatr['name']));
				$tmp_img = $fileAvatr['tmp_name'];
				$size = $fileAvatr['size'];
				$type=strtolower(substr($img,-3));
				if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
					# Upload
					if(isImage($img)) {
						$new_img = $img;
						move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
						if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
						resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
						resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
						resizes($gallery_path,$gallery_path,'l_'.$img,'m_'.$new_img,DEFAULT_WIDTH_PROJECT,DEFAULT_HIGHT_PROJECT,DEFAULT_PHOTO_QUALITY);
						
						if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
						$avatar = $new_img;
					} 
				} #/if (preg_match
			}
			# Password
			$pass = $request->element('password','');
			$password = md5($pass);
			#Property 
			$properties = $serviceInfo->getProperties();
			$properties = array('about' => $request->element('about'),
								'password' => $pass,
								'avatar' => $avatar,
								'nick_yahoo' => Filter($request->element('nick_yahoo')),
								'nick_skype' => Filter($request->element('nick_skype')));
			# Custom fields
			foreach($fieldList as $field) {
				$properties[$field->getName()] = stripslashes($request->element($field->getName()));
			}
			# End property
			
			$data = array('store_id' => $storeId,
						  'username' => $request->element('username'),
						  'slug' => $slug,
						  'password' => $password,
						  'area_id'  => $request->element('area_id'),
						  'fullname' => Filter($request->element('fullname')),
						  'address' => Filter($request->element('address')),
						  'email' => Filter($request->element('email')),
						  'tel' => Filter($request->element('tel')),
						  'properties' => serialize($properties),
						  'date_created' => date("Y-m-d H:i:s"),
						  'status' => $request->element('status'));
			$newId = $businesss->addData($data);
					
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_business'],$request->element('username')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=business&mod=list&rcode=6");
		}
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['username'] = $validate->validUsername($request->element('username'),$amessages['username']);
	$error['INPUT']['password'] = $validate->validPassword($request->element('password'),$amessages['password']);
	$error['INPUT']['cpassword'] = $validate->validTestPass($request->element('password'),$request->element('cpassword'),$amessages['cpassword']);
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'),$amessages['fullname']);
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$amessages['address']);
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'),$amessages['email']);
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'),$amessages['telephone']);
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	
	if($error['INPUT']['username']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['address']['error'] || $error['INPUT']['tel']['error'] || $error['INPUT']['password']['error'] || $error['INPUT']['cpassword']['error'] || $error['INPUT']['email']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>