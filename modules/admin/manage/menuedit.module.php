<?php
/*************************************************************************
Editing product module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Coder: Mai Minh
Email: info@derasoft.com
Last updated: 04/09/2011
Checked by: Mai Minh (29/09/2011)
**************************************************************************/
$userInfo->checkPermission('menu','edit');
$templateFile = 'managemenu.tpl.html';
include_once(ROOT_PATH.'classes/dao/menucategories.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH.'classes/dao/menus.class.php');
$menuCategories = new menuCategories();
$fields = new Fields($storeId);
$menus = new Menus($storeId);
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_menu'] => '/'.ADMIN_SCRIPT.'?op=manage&act=menu',
				$amessages['edit_item'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=menu';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_menu'] => '#',
				$amessages['list_menu_category'] => $tabLink.'&mod=listcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

# Get list of custom fields
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='menu'",array('position' => 'ASC'));
if($fieldList) $template->assign('fieldList',$fieldList);
		
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$menuInfo = $menus->getObject($id);
if(!$menuInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			# Category combo box
			$categoryCombo = $menuCategories->generateCombo($request->element('cat_id'));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			# Menus combo box
			$menuCombo = $menus->generateCombo($request->element('parent_id',0));
			if($menuCombo) $template->assign('menuCombo',$menuCombo);
		} else { # Valid data input
			# Category combo box
			$categoryCombo = $menuCategories->generateCombo($request->element('cat_id'));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			# Menu combo box
			$menuCombo = $menus->generateCombo($request->element('parent_id',0));
			if($menuCombo) $template->assign('menuCombo',$menuCombo);
					
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$menuInfo = $menus->getObject($id);
				if($menuInfo) {			
					$properties = $menuInfo->getProperties();
					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = $request->element($field->getName());
					}
					
					$data = array('store_id' => $storeId,
								  'parent_id' => $request->element('parent_id'),
								  'mc_id' => $request->element('cat_id'),
								  'name' => Filter($request->element('name')),
								  'position' => $request->element('position'),
								  'status' => $request->element('status'),
								  'url' => Filter($request->element('url')),
								  'properties' => serialize($properties));
					$menus->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_menu'],$request->element('name')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=menu&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load product category information to edit
		$template->assign('item',$menuInfo);
		# Category combo box
		$categoryCombo = $menuCategories->generateCombo($menuInfo->getCId());
		if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		
		# Menu combo box
		$menuCombo = $menus->generateCombo($menuInfo->getParentId());
		if($menuCombo) $template->assign('menuCombo',$menuCombo);
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['parent_id'] = $validate->pasteString($request->element('parent_id'));
	$error['INPUT']['cat_id'] = $validate->pasteString($request->element('cat_id'));
	$error['INPUT']['position'] = $validate->validNumber($request->element('position'),$amessages['position']);
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['url'] = $validate->pasteString($request->element('url'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	if($error['INPUT']['name']['error'] || $error['INPUT']['position']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>