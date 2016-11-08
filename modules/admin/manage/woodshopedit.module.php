<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'managewoodshop.tpl.html';
include_once(ROOT_PATH.'classes/dao/woodshopcategories.class.php');
include_once(ROOT_PATH.'classes/dao/woodshop.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$woodshopCategories = new WoodshopCategories($storeId);
$woodshop = new Woodshop($storeId);
$gallery_path = ROOT_PATH."upload/1/woodshop/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_product'] => '/'.ADMIN_SCRIPT.'?op=manage&act=woodshop',
				$amessages['update_banner_category'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=woodshop';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_woodshop'] => '#',
				$amessages['list_woodshop_category'] => $tabLink.'&mod=listcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$woodshopInfo = $woodshop->getObject($id);
if(!$woodshopInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	if($request->element('doo') == 'delAvatar') {
		$woodshopInfo = $woodshop->getObject($id);
		$properties = $woodshopInfo->getProperties();
		if($woodshopInfo->getProperty('avatar')) {
			unlink($gallery_path.'a_'.$woodshopInfo->getProperty('avatar'));
			unlink($gallery_path.'l_'.$woodshopInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$woodshop->updateData($data,$id);
			$woodshopInfo = $woodshop->getObject($id);
		}
	}
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$template->assign('itemInfo',$woodshopInfo);
			
			# Category combo box
			$categoryCombo = $woodshopCategories->generateCombo($request->element('gid',1));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		} else { # Valid data input
			# Category combo box
			$categoryCombo = $woodshopCategories->generateCombo($request->element('gid',1));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);	
						
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$woodshopInfo = $woodshop->getObject($id);
				if($woodshopInfo) {			
					$properties = $woodshopInfo->getProperties();
					
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
								
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($woodshopInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$woodshopInfo->getProperty('avatar'));
									
									unlink($gallery_path.'l_'.$woodshopInfo->getProperty('avatar'));
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
					$woodshop->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_woodshop'],$request->element('id')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=woodshop&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load woodshop category information to edit
		$template->assign('item',$woodshopInfo);

		# Category combo box
		$categoryCombo = $woodshopCategories->generateCombo($woodshopInfo->getParentId(),1);
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
