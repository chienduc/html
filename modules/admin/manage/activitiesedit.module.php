<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'manageactivities.tpl.html';
include_once(ROOT_PATH.'classes/dao/activitiescategories.class.php');
include_once(ROOT_PATH.'classes/dao/activities.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$activitiesCategories = new ActivitiesCategories($storeId);
$activities = new Activities($storeId);
$gallery_path = ROOT_PATH."upload/1/activities/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_product'] => '/'.ADMIN_SCRIPT.'?op=manage&act=activities',
				$amessages['update_banner_category'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=activities';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_activities'] => '#',
				$amessages['list_activities_category'] => $tabLink.'&mod=listcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$activitiesInfo = $activities->getObject($id);
if(!$activitiesInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	if($request->element('doo') == 'delAvatar') {
		$activitiesInfo = $activities->getObject($id);
		$properties = $activitiesInfo->getProperties();
		if($activitiesInfo->getProperty('avatar')) {
			unlink($gallery_path.'a_'.$activitiesInfo->getProperty('avatar'));
			unlink($gallery_path.'l_'.$activitiesInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$activities->updateData($data,$id);
			$activitiesInfo = $activities->getObject($id);
		}
	}
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$template->assign('itemInfo',$activitiesInfo);
			
			# Category combo box
			$categoryCombo = $activitiesCategories->generateCombo($request->element('gid',1));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		} else { # Valid data input
			# Category combo box
			$categoryCombo = $activitiesCategories->generateCombo($request->element('gid',1));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);	
						
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$activitiesInfo = $activities->getObject($id);
				if($activitiesInfo) {			
					$properties = $activitiesInfo->getProperties();
					
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

								resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,150,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
								
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($activitiesInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$activitiesInfo->getProperty('avatar'));
									
									unlink($gallery_path.'l_'.$activitiesInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} 
					}
					
					# End File upload
					$data = array('store_id' => $storeId,
								  'wid' => $request->element('wid'),
								  'name' => $request->element('name'),
								  'position' => $request->element('position'),
								  'status' => $request->element('status'),
								  'properties' => serialize($properties));
					$activities->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_activities'],$request->element('id')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=activities&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load activities category information to edit
		$template->assign('item',$activitiesInfo);

		# Category combo box
		$categoryCombo = $activitiesCategories->generateCombo($activitiesInfo->getParentId(),1);
		if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
	}
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['wid'] = $validate->pasteString($request->element('wid'));
	$error['INPUT']['position'] = $validate->validNumber($request->element('position'),$amessages['position']);
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
if($error['INPUT']['wid']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>
