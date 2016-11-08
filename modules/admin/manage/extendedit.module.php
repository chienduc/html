<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'manageextend.tpl.html';
include_once(ROOT_PATH.'classes/dao/extends.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$extend = new Extend();
$fields = new Fields($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."extend/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_extend'] => '/'.ADMIN_SCRIPT.'?op=manage&act=extend',
				$amessages['edit_extend'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=extend';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_extend'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$extendInfo = $extend->getObject($id);
if(!$extendInfo) {
	$template->assign('validItem',0);
} else{
	$template->assign('validItem',1);

	# Allow some javascript
	$template->assign('ckEditor',1);
    if($request->element('doo') == 'delPhoto') { 
		$extendInfo = $extend->getObject($id);
		$properties = $extendInfo->getProperties();
		foreach($properties['photos'] as $key => $value) {
			if($value == $request->element('photo')) {
				unlink($gallery_path."l_".$properties['photos'][$key]);
				unlink($gallery_path.preg_replace("/(png$|bmp$|gif$)/","jpg",$properties['photos'][$key]));
				unset($properties['photos'][$key]);
				$data = array('properties' => serialize($properties));
				$extend->updateData($data,$id);
				$extendInfo = $extend->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delAvatar') {
		$extendInfo = $extend->getObject($id);
		$properties = $extendInfo->getProperties();
		if($extendInfo->getProperty('avatar')) {
			unlink($gallery_path.$extendInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$extend->updateData($data,$id);
			$extendInfo = $extend->getObject($id);
		}
	}	
	if($request->element('doo') == 'delMovie') {
		$extendInfo = $extend->getObject($id);
		$properties = $extendInfo->getProperties();
		foreach($properties['movies'] as $key => $value) {
			if($value == $request->element('movie')) {
				unlink($gallery_path.$properties['movies'][$key]);
				unset($properties['movies'][$key]);
				$data = array('properties' => serialize($properties));
				$extend->updateData($data,$id);
				$extendInfo = $extend->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delFile') {
		$extendInfo = $extend->getObject($id);
		$properties = $extendInfo->getProperties();
		foreach($properties['files'] as $key => $value) {
			if($value == $request->element('file')) {
				unlink($gallery_path.$properties['files'][$key]);
				unset($properties['files'][$key]);
				$data = array('properties' => serialize($properties));
				$extend->updateData($data,$id);
				$extendInfo = $extend->getObject($id);
				break;
			}
		}
	}
	
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='extend'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$extendInfo = $extend->getObject($id);
			$template->assign('itemInfo',$extendInfo);
			
		} else { # Valid data input
			
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$extendInfo = $extend->getObject($id);
				if($extendInfo) {			
					$properties = $extendInfo->getProperties();
				
					#User update
					$properties['user_update'] = $userInfo->getUsername();
					
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
								if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
								resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
								resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($extendInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$extendInfo->getProperty('avatar'));
									unlink($gallery_path.'l_'.$extendInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} #/if (preg_match
					}
					# Files upload
					$files = isset($_FILES['files'])?$_FILES['files']:'';
					if($files) {
						if(!isset($properties['photos'])) $properties['photos'] = array();
						if(!isset($properties['videos'])) $properties['videos'] = array();
						if(!isset($properties['files'])) $properties['files'] = array();
						for($i=0; $i<count($files['name']);$i++) {
							$img = addslashes(Filter(rand()."_".$files['name'][$i]));
							$tmp_img = $files['tmp_name'][$i];
							$size = $files['size'][$i];
							$type=strtolower(substr($img,-3));
							if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
								# Upload
								if(isImage($img)) {
									$new_img = $img;
									move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
									if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
										resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
								         resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);								
								
									$properties['photos'][] = $img;
								} elseif(isVideo($img)) {
									move_uploaded_file($tmp_img,$gallery_path.$img);
									$properties['videos'][] = $img;
								} else {
									move_uploaded_file($tmp_img,$gallery_path.$img);
									$properties['files'][] = $img;
								}
							} #/if (preg_match
						} #/for($i=0;
					}
					
					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
			
					$data = array('name' => Filter($request->element('name')),
								  'position'   => $request->element('position'),
								  'detail' => $request->element('detail'),
								  'url' => $request->element('url'),
								  'status' => $request->element('status'),
								  'properties' => serialize($properties));
					$extend->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_extend'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=extend&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load extend category information to edit
		$template->assign('item',$extendInfo);
		
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='extend'",array('position' => 'ASC'));
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
	$error['INPUT']['url'] = $validate->validString($request->element('url'));
	$error['INPUT']['detail'] = $validate->validString($request->element('detail'),$amessages['detail']);
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		
	}
		
 if($error['INPUT']['name']['error'] || $error['INPUT']['detail']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>