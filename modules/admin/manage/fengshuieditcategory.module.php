<?php
/*************************************************************************
Editing Aticle Categorey module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Email: info@derasoft.com
Last updated: 09/09/2011
Coder: Xuyen Tran
Checked by: Mai Minh (21/09/2011)
**************************************************************************/

$userInfo->checkPermission('category','edit');
$templateFile = 'managefengshui.tpl.html';
include_once(ROOT_PATH.'classes/dao/fengshuicategories.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/fields.class.php');
$fields = new Fields($storeId);
$fengshuiCategories = new FengshuiCategories($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."fengshuicategories/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_fengshui'] => '/'.ADMIN_SCRIPT.'?op=manage&act=fengshui',
				$amessages['edit_fengshui_category'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=fengshui';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => $tabLink.'&mod=add',
				$amessages['list_fengshui_category'] => $tabLink.'&mod=listcategory',
				$amessages['edit_fengshui_category'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',4);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$categoryInfo = $fengshuiCategories->getObject($id);

if(!$categoryInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	if($request->element('doo') == 'delAvatar') {
		$categoryInfo = $fengshuiCategories->getObject($id);
		$properties = $categoryInfo->getProperties();
		if($categoryInfo->getProperty('avatar')) {
			unlink($gallery_path.$categoryInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$fengshuiCategories->updateData($data,$id);
			$categoryInfo = $fengshuiCategories->getObject($id);
		}
	}

	# Allow some javascript
	$template->assign('ckEditor',1);

	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
	
			# Category combo box
			$categoryCombo = $fengshuiCategories->generateCombo($request->element('parent_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			# Get list of custom fields by Thai Nguyen
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='fengshuiCategories'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
			# Change by Thai Nguyen
		} else { # Valid data input
			# Category combo box
			$categoryCombo = $fengshuiCategories->generateCombo($request->element('parent_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			# Get list of custom fields by Thai Nguyen
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='fengshuiCategories'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
			# Change by Thai Nguyen
			
			# check duplicate category name
			if($fengshuiCategories->checkDuplicate($request->element('name'),'name',"`id` <> '$id'")) {
				$validate['INPUT']['name']['message'] = $amessages['name_duplicated'];
				$validate['INPUT']['name']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
			
			# Check if duplicate slug
			$slug = TextFilter::urlize($request->element('slug'),false,'-');
			if($fengshuiCategories->checkDuplicate($slug,'slug',"`id` <> '$id'")) {
				$validate['INPUT']['slug']['message'] = $amessages['slug_duplicated'];
				$validate['INPUT']['slug']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
					
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$properties = $categoryInfo->getProperties();
				
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
								resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
								resize($gallery_path,$gallery_path,'l_'.$img,'t_'.$new_img,DEFAULT_THUMBNAIL_SIZE,DEFAULT_THUMBNAIL_SQUARE,DEFAULT_PHOTO_QUALITY);
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($categoryInfo->getProperty('avatar')) {
									unlink($gallery_path.$categoryInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} #/if (preg_match
					}
	
				$properties['sort_type'] = $request->element('sort_type');
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
							  'parent_id' => Filter($request->element('parent_id')),
							  'slug' => Filter($slug),
							  'name' => Filter($request->element('name')),
							  'keyword' => Filter($request->element('keyword')),
							  'sapo' => Filter($request->element('sapo')),
							  'position' => Filter($request->element('position')),
							  'status' => Filter($request->element('status')),
							  'properties' => serialize($properties));
				$fengshuiCategories->updateData($data,$id);
				
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_fengshui_category'],$fengshuiCategories->getNameFromId($id)),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
				
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=fengshui&mod=editcategory&lang=$lang&id=$id&rcode=7");
			}
		}
	} else { # Load product category information to edit
		$template->assign('item',$categoryInfo);
	
		# Category combo box
		$categoryCombo = $fengshuiCategories->generateCombo($categoryInfo->getParentId());
		if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		# Get list of custom fields by Thai Nguyen
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='fengshuiCategories'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		# Change by Thai Nguyen
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['parent_id'] = $validate->pasteString($request->element('parent_id'));
	$error['INPUT']['slug'] = $validate->validString($request->element('slug'),$amessages['slug']);
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['keyword'] = $validate->validString($request->element('keyword'),$amessages['keyword']);
	$error['INPUT']['sapo'] = $validate->validString($request->element('sapo'),$amessages['sapo']);
	$error['INPUT']['position'] = $validate->pasteString($request->element('position'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	$error['INPUT']['sort_type'] = $validate->pasteString($request->element('sort_type','position'));
	$error['INPUT']['sort_dir'] = $validate->pasteString($request->element('sort_dir','ASC'));
	$error['INPUT']['display'] = $validate->pasteString($request->element('display',0));
	$error['INPUT']['ipp'] = $validate->validInteger($request->element('ipp'),$amessages['items_per_page']);
	$error['INPUT']['landing'] = $validate->pasteString($request->element('landing'));
	if($error['INPUT']['landing']['value']) $error['INPUT']['landing_page'] = $validate->validString($request->element('landing_page'),$amessages['landing_page']);
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	if($error['INPUT']['name']['error'] || $error['INPUT']['keyword']['error'] || $error['INPUT']['sapo']['error'] || $error['INPUT']['ipp']['error'] || $error['INPUT']['slug']['error'] || $error['INPUT']['landing']['error'] || $error['INPUT']['landing_page']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>