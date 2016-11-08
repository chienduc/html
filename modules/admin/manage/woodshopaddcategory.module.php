<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'managewoodshop.tpl.html';
include_once(ROOT_PATH.'classes/dao/woodshopcategories.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/fields.class.php');
$woodshopCategories = new WoodshopCategories($storeId);
$fields = new Fields($storeId);
$gallery_path = ROOT_PATH."upload/1/woodshop/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['add_woodshop_category'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=woodshop';
$listTabs = array(
				$amessages['list_woodshop_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_woodshop_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

# Get list of custom fields
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='woodshopcategories'",array('position' => 'ASC'));
if($fieldList) $template->assign('fieldList',$fieldList);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);
	} else { # Valid data input
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			$properties = array('');
			# Check if gallery folder is exists
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
				    	resize($gallery_path,$gallery_path,'l_'.$img,'m_'.$new_img,210,DEFAULT_MEDIUM_SQUARE,DEFAULT_PHOTO_QUALITY);
						resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
						if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
						$avatar = $new_img;
					} 
				} #/if (preg_match
			}
			$properties['ipp'] = 20;
			$properties['avatar'] = $avatar;
			# Custom fields
			foreach($fieldList as $field) {
				$properties[$field->getName()] = stripslashes($request->element($field->getName()));
			}
			$data = array('store_id' => $storeId,
						  'name' => Filter($request->element('name')),
						  'works' => Filter($request->element('works')),
						  'date_complete' =>Changedate($request->element('date_complete')),
						  'status' => $request->element('status'),
						  'properties' => serialize($properties));  
			$woodshopCategories->addData($data);
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_ads_category'],$request->element('name')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=woodshop&mod=listcategory&pId=".$request->element('parent_id')."&rcode=6");
		}
	}
}

# Check data input
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['works'] = $validate->validString($request->element('works'),$amessages['works']);
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
	}
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));	
	if($error['INPUT']['name']['error'] || $error['INPUT']['works']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>