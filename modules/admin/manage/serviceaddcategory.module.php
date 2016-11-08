<?php
/*************************************************************************
Adding service category module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Email: info@derasoft.com
Coder: Mai Minh
Last updated: 05/05/2012
**************************************************************************/
$userInfo->checkPermission('category','add');
$templateFile = 'manageservice.tpl.html';
include_once(ROOT_PATH.'classes/dao/servicecategories.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/fields.class.php');
$fields = new Fields($storeId);
$serviceCategories = new ServiceCategories($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."servicecategories/";
$topNav = array(
				$amessages['add_service_category'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=service';
$listTabs = array(
				$amessages['list_service_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_service_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

# Category combo box
$categoryCombo = $serviceCategories->generateCombo($request->element('pId'));
if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);

# Get list of custom fields by Thai Nguyen
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='serviceCategories'",array('position' => 'ASC'));
if($fieldList) $template->assign('fieldList',$fieldList);
# Change by Thai Nguyen
# Allow some javascript
$template->assign('ckEditor',1);
	
if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);
	} else { # Valid data input
	
		# check duplicate category name
		if($serviceCategories->checkDuplicate($request->element('name'))) {
			$validate['INPUT']['name']['message'] = $amessages['name_duplicated'];
			$validate['INPUT']['name']['error'] = 1;
			$validate['invalid'] = 1;
			$template->assign('error',$validate);
		}
		
		# Check if duplicate slug
		$slug = TextFilter::urlize($request->element('name'),false,'-');
		$i = 0;
		$dup = 1;
		while($dup) {
			$dup = $serviceCategories->checkDuplicate($slug.($i?'-'.$i:''),'slug');
			if($dup) $i++;
		}
		$slug .= $i?'-'.$i:'';
		
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			$properties = array('');
			# Check if gallery folder is exists
			if(!file_exists($gallery_root)) mkdir("$gallery_root");
			if(!file_exists($gallery_path)) mkdir("$gallery_path");
			
			
			# File Avatar
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
						resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
						resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_THUMBNAIL_SIZE,DEFAULT_THUMBNAIL_SQUARE,DEFAULT_PHOTO_QUALITY);
						if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
						$avatar = $new_img;
					} 
				} #/if (preg_match
			}
			$properties['sort_type'] = $request->element('sort_type');
			$properties['avatar'] = $avatar;
			$properties['sort_dir'] = $request->element('sort_dir');
			$properties['display'] = $request->element('display');
			$properties['ipp'] = $request->element('ipp');
			$properties['landing'] = $request->element('landing');
			$properties['landing_page'] = $request->element('landing_page');
			# Custom fields
			foreach($fieldList as $field) {
				$properties[$field->getName()] = $request->element($field->getName());
			}
			$data = array('store_id' => $storeId,
						  'parent_id' => $request->element('parent_id'),
						  'slug' => $slug,
						  'name' => Filter($request->element('name')),
						  'keyword' => Filter($request->element('keyword')),
						  'sapo' => Filter($request->element('sapo')),
						  'position' => $request->element('position'),
						  'status' => $request->element('status'),
						  'properties' => serialize($properties));
			$serviceCategories->addData($data);
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_service_category'],$request->element('name')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=service&mod=listcategory&pId=".$request->element('parent_id')."&rcode=6");
		}
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['parent_id'] = $validate->pasteString($request->element('parent_id'));
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);

	$error['INPUT']['position'] = $validate->pasteString($request->element('position'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));


	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	# End change by Thai Nguyen
	if($error['INPUT']['name']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>