<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'managestaff.tpl.html';
include_once(ROOT_PATH.'classes/dao/staffcategories.class.php');
include_once(ROOT_PATH.'classes/dao/staff.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$staffCategories = new StaffCategories($storeId);
$staff = new Staff($storeId);
$fields = new Fields($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."staff/";
$gallery_pdf  = $gallery_path."pdf/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_staff'] => '/'.ADMIN_SCRIPT.'?op=manage&act=staff',
				$amessages['edit_staff'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=staff';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_staff'] => '#',
				$amessages['list_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_staff_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$staffInfo = $staff->getObject($id);
if(!$staffInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	if($request->element('doo') == 'delAvatar') {
		$staffInfo = $staff->getObject($id);
		$properties = $staffInfo->getProperties();
		if($staffInfo->getProperty('avatar')) {
			unlink($gallery_path.'a_'.$staffInfo->getProperty('avatar'));
			unlink($gallery_path.'l_'.$staffInfo->getProperty('avatar'));
			unlink($gallery_path.'m_'.$staffInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$staff->updateData($data,$id);
			$staffInfo = $staff->getObject($id);
		}
	}	
   if($request->element('doo') == 'delPdf') {
		$staffInfo = $staff->getObject($id);
		$properties = $staffInfo->getProperties();
		if($staffInfo->getProperty('pdf')) {
			unlink($gallery_pdf.$staffInfo->getProperty('pdf'));
			unset($properties['pdf']);
			$data = array('properties' => serialize($properties));
			$staff->updateData($data,$id);
			$staffInfo = $staff->getObject($id);
		}
	}


	# Allow some javascript
	$template->assign('ckEditor',1);

	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='staff'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$staffInfo = $staff->getObject($id);
			$template->assign('itemInfo',$staffInfo);
			
			# Category combo box
			$categoryCombo = $staffCategories->generateCombo($request->element('cat_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		} else { # Valid data input
			# Category combo box
			$categoryCombo = $staffCategories->generateCombo($request->element('cat_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
				
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$staffInfo = $staff->getObject($id);
				if($staffInfo) {			
					$properties = $staffInfo->getProperties();
					
					# Check if gallery folder is exists
					if(!file_exists($gallery_root)) mkdir("$gallery_root");
					if(!file_exists($gallery_path)) mkdir("$gallery_path");
					if(!file_exists($gallery_pdf)) mkdir("$gallery_pdf");
										
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
								resize($gallery_path,$gallery_path,'l_'.$img,'m_'.$new_img,281,DEFAULT_MEDIUM_SQUARE,DEFAULT_PHOTO_QUALITY);
							
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($staffInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$staffInfo->getProperty('avatar'));
									unlink($gallery_path.'t_'.$staffInfo->getProperty('avatar'));
									unlink($gallery_path.'m_'.$staffInfo->getProperty('avatar'));
									unlink($gallery_path.'l_'.$staffInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} #/if (preg_match
					}
					# Files upload
					$files = isset($_FILES['files'])?$_FILES['files']:'';
					if($files) {
						if(!isset($properties['pdf'])) $properties['pdf'] = '';
							$img = addslashes(Filter(rand()."_".$files['name']));
							$tmp_img = $files['tmp_name'];
							$size = $files['size'];
							$type=strtolower(substr($img,-3));
							if(preg_match("/".ALLOW_FILE_PDF."/",strtolower($img))) {
							# Upload
					             move_uploaded_file($tmp_img,$gallery_pdf.$img);
								$properties['pdf']= $img;
								
							} 
					}
					# End File upload
					#User update
					$properties['user_update'] = $userInfo->getUsername();

					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
					$data = array('store_id' => $storeId,
								  'cat_id' => $request->element('cat_id'),
								  'name' => Filter($request->element('name')),
								  'office' => Filter($request->element('office')),
								
								  'position' => $request->element('position'),
								  'status' => $request->element('status'),
								  'properties' => serialize($properties),
								  'updated' => date("Y-m-d H:i:s"));
					$staff->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_staff'],$request->element('name')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=staff&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load staff information to edit
			$template->assign('item',$staffInfo);
	
			# Category combo box
			$categoryCombo = $staffCategories->generateCombo($staffInfo->getCatId(),1);
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			
			# Get list of custom fields
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='staff'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['cat_id'] = $validate->pasteString($request->element('cat_id'));
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['office'] = $validate->validString($request->element('office'),$amessages['office']);
	$error['INPUT']['position'] = $validate->pasteString($request->element('position'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));

	
	$error['INPUT']['description'] = $validate->validString($request->element('description'));

	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
	}
	
	if($error['INPUT']['name']['error'] || $error['INPUT']['office']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>