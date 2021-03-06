<?php
/*************************************************************************
Editing article module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Last updated: 09/09/2011
Coder: Tran Thi My Xuyen
**************************************************************************/
$userInfo->checkPermission('business','edit');
$templateFile = 'managebusiness.tpl.html';
include_once(ROOT_PATH.'classes/dao/businesss.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/areas.class.php');
$businesss = new Businesss($storeId);
$fields = new Fields($storeId);
$areas = new Areas(1);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."businesss/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_business'] => '/'.ADMIN_SCRIPT.'?op=manage&act=business',
				$amessages['edit_business'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=business';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_business'] => '',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);
# Allow some javascript
$template->assign('ckEditor',1);
$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$businessInfo = $businesss->getObject($id);

if(!$businessInfo) {
	$template->assign('validItem',0);
} else {
	
	if($request->element('doo') == 'delAvatar') {
		$businessInfo = $businesss->getObject($id);
		$properties = $businessInfo->getProperties();
		if($businessInfo->getProperty('avatar')) {
			unlink($gallery_path.'a_'.$businessInfo->getProperty('avatar'));
			unlink($gallery_path.'l_'.$businessInfo->getProperty('avatar'));
			unlink($gallery_path.'m_'.$businessInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$businesss->updateData($data,$id);
			$businessInfo = $businesss->getObject($id);
		}
	}
	
	
	$template->assign('validItem',1);
if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='business'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		$comboArea=$areas->generateCombo($request->element('area_id'));
        $template->assign('comboArea',$comboArea);
		
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);
		$businessInfo = $businesss->getObject($id);
		$template->assign('businessInfo',$businessInfo);
	} else { 
		if($request->element('password')) {
				$new_password = md5($request->element('password'));
				$cpassword = md5($request->element('cpassword'));
				if($new_password != $cpassword) { # New password is same as confirm password
					$validate['INPUT']['cpassword']['message'] = $amessages['invalid_confirm_password'];
					$validate['INPUT']['cpassword']['error'] = 1;
					$validate['invalid'] = 1;
					$template->assign('error',$validate);
				}
			}		
			# check duplicate email
			if($businesss->checkDuplicate($request->element('email'),'email',"`id` <>'$id'")) {
				$validate['INPUT']['email']['message'] = $amessages['email_duplicated'];
				$validate['INPUT']['email']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
			
		# Check if duplicate slug
			$slug = TextFilter::urlize($request->element('slug'),false,'-');
			$i = 0;
			$dup = 1;
			while($dup) {
			$dup = $businesss->checkDuplicate($slug.($i?'-'.$i:''),'slug',"`id` <> '$id'");
				if($dup) $i++;
			}
			$slug .= $i?'-'.$i:'';
		# Everything is ok. Update data to DB
		if(!$validate['invalid']) {
			$businessInfo = $businesss->getObject($id);
			if($businessInfo) {			
				#User update
				$properties = $businessInfo->getProperties();
				# Check if gallery folder is exists
					if(!file_exists($gallery_root)) mkdir("$gallery_root");
					if(!file_exists($gallery_path)) mkdir("$gallery_path");
										
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
								resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
								resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
								resizes($gallery_path,$gallery_path,'l_'.$img,'m_'.$new_img,DEFAULT_WIDTH_PROJECT,DEFAULT_HIGHT_PROJECT,DEFAULT_PHOTO_QUALITY);
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($businessInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$businessInfo->getProperty('avatar'));
									unlink($gallery_path.'t_'.$businessInfo->getProperty('avatar'));
									unlink($gallery_path.'m_'.$businessInfo->getProperty('avatar'));
									unlink($gallery_path.'l_'.$businessInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} #/if (preg_match
					}
				$properties['about'] = $request->element('about');
				if($request->element('password')) $properties['password'] = $request->element('password');
				$properties['nick_yahoo'] = Filter($request->element('nick_yahoo'));
				$properties['nick_skype'] = Filter($request->element('nick_skype'));
				# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
			   $data = array('store_id' => $storeId,
			   			  'fullname' => Filter($request->element('fullname')),
						  'address' => Filter($request->element('address')),
						  'slug' => $slug,
						  'email' => Filter($request->element('email')),
						  'area_id' => $request->element('area_id'),
						  'tel' => Filter($request->element('tel')),
						  'properties' => serialize($properties),
						  'date_created' => date("Y-m-d H:i:s"),
						  'status' => $request->element('status'));
				if($request->element('password')) $data['password'] = md5($request->element('password'));
				$businesss->updateData($data,$id);
				
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_business'],$request->element('username')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=business&mod=edit&lang=$lang&id=$id&rcode=7");
			}
		}
	}
} else { # Load business information to edit
	$businessInfo = $businesss->getObject($id);
	if($businessInfo) {
		$template->assign('item',$businessInfo);
	}
	$comboArea=$areas->generateCombo($businessInfo->getAreaId());
    $template->assign('comboArea',$comboArea);
}
# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='business'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['username'] = $validate->pasteString($request->element('username'));
	$error['INPUT']['slug'] = $validate->validString($request->element('slug'),$amessages['slug']);
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'));
	if($request->element('password')) $error['INPUT']['password'] = $validate->validPassword($request->element('password'));
	else $error['INPUT']['password'] = $validate->pasteString($request->element('password'));
	if($request->element('cpassword')) $error['INPUT']['cpassword'] = $validate->validPassword($request->element('cpassword'),$amessages['cpassword']);
	else $error['INPUT']['cpassword'] = $validate->pasteString($request->element('cpassword'));
	$error['INPUT']['address'] = $validate->validString($request->element('address'));
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'));
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	
	if($error['INPUT']['username']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['password']['error'] || $error['INPUT']['address']['error'] || $error['INPUT']['tel']['error']  || $error['INPUT']['email']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>