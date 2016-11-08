<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'manageactivities.tpl.html';
include_once(ROOT_PATH.'classes/dao/activitiescategories.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/fields.class.php');
$activitiesCategories = new ActivitiesCategories($storeId);
$fields = new Fields($storeId);
$gallery_path = ROOT_PATH."upload/1/activities/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_banner'] => '/'.ADMIN_SCRIPT.'?op=manage&act=activities',
				$amessages['update_banner_category'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=activities';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => $tabLink.'&mod=add',
				$amessages['list_activities_category'] => $tabLink.'&mod=listcategory',
				$amessages['edit_activities_category'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',4);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

$id = $request->element('id');
if($id) $template->assign('id',$id);
$categoryInfo = $activitiesCategories->getObject($id);
if(!$categoryInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	if($request->element('doo') == 'delAvatar') {
		$categoryInfo = $activitiesCategories->getObject($id);
		$properties = $categoryInfo->getProperties();
		if($categoryInfo->getProperty('avatar')) {
			unlink($gallery_path.'a_'.$categoryInfo->getProperty('avatar'));
			unlink($gallery_path.'m_'.$categoryInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$activitiesCategories->updateData($data,$id);
			$categoryInfo = $activitiesCategories->getObject($id);
		}
	}
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	       # Get list of custom fields
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='activitiescategories'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
	# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$categoryInfo = $activitiesCategories->getObject($id);
			if($categoryInfo) {
				   $template->assign('catInfo',$categoryInfo);
			}
		
		} else { # Valid data input
			if(!$validate['invalid']) {				
			    $properties = $categoryInfo->getProperties();;
				# Check if gallery folder is exists
				if(!file_exists($gallery_path)) mkdir("$gallery_path");
				# Files upload
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

								resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
								resize($gallery_path,$gallery_path,'l_'.$img,'m_'.$new_img,289,DEFAULT_MEDIUM_SQUARE,DEFAULT_PHOTO_QUALITY);
								
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($categoryInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$categoryInfo->getProperty('avatar'));
									
									unlink($gallery_path.'l_'.$categoryInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} 
					}
				$properties['ipp'] = $request->element('ipp');
				# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
				$data = array('store_id' => $storeId,
							  'name' => Filter($request->element('name')),
							  'content' => Filter($request->element('content')),
							  'status' => $request->element('status'),
							  'properties' => serialize($properties));
				$activitiesCategories->updateData($data,$id);
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_activities_category'],$activitiesCategories->getNameFromId($id)),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
				
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=activities&mod=editcategory&lang=$lang&id=$id&rcode=7");
			}
		}
	} else { # Load product category information to edit
		   $template->assign('item',$categoryInfo);
		   # Get list of custom fields
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='activitiescategories'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['content'] = $validate->validString($request->element('content'),$amessages['content']);
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
	}
	if($error['INPUT']['name']['error'] || $error['INPUT']['content']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>