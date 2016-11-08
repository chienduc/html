<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'manageawards.tpl.html';
include_once(ROOT_PATH.'classes/dao/awards.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/fields.class.php');
$awards = new Awards($storeId);
$fields = new Fields($storeId);
$gallery_path = ROOT_PATH."upload/1/awards/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_awards'] => '/'.ADMIN_SCRIPT.'?op=manage&act=awards',
				$amessages['edit_awards'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=awards';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_awards'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$awardsInfo = $awards->getObject($id);
if(!$awardsInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	if($request->element('doo') == 'delAvatar') {
		$awardsInfo = $awards->getObject($id);
		$properties = $awardsInfo->getProperties();
		if($awardsInfo->getProperty('avatar')) {
			unlink($gallery_path.'a_'.$awardsInfo->getProperty('avatar'));
			unlink($gallery_path.'l_'.$awardsInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$awards->updateData($data,$id);
			$awardsInfo = $awards->getObject($id);
		}
	}
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	         # Get list of custom fields
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='awards'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$template->assign('itemInfo',$awardsInfo);
		
		} else { # Valid data input
			
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$awardsInfo = $awards->getObject($id);
				if($awardsInfo) {			
					$properties = $awardsInfo->getProperties();
					
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

								resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,118,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
								
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($awardsInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$awardsInfo->getProperty('avatar'));
									
									unlink($gallery_path.'l_'.$awardsInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} 
					}
					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
					# End File upload
					$data = array('store_id' => $storeId,
								  'name' => $request->element('name'),
								  'position' => $request->element('position'),
								  'status' => $request->element('status'),
								  'properties' => serialize($properties));
					$awards->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_awards'],$request->element('id')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=awards&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load awards category information to edit
		$template->assign('item',$awardsInfo);
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='awards'",array('position' => 'ASC'));
	  if($fieldList) $template->assign('fieldList',$fieldList);
	}
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['position'] = $validate->validNumber($request->element('position'),$amessages['position']);
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
	}
	
if($error['INPUT']['name']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>
