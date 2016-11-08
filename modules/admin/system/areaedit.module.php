<?php
/*************************************************************************
Editing Area module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Email: info@derasoft.com
Last updated: 09/09/2011
Coder: Xuyen Tran
Checked by: Mai Minh (21/09/2011)
**************************************************************************/
$templateFile = 'systemarea.tpl.html';
include_once(ROOT_PATH.'classes/dao/areas.class.php');
include_once(ROOT_PATH.'classes/dao/shippingmethods.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$areas = new Areas($storeId);
$shippingMethods = new ShippingMethods($storeId);

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['system'] => '/'.ADMIN_SCRIPT.'?op=system',
				$amessages['manage_area'] => '/'.ADMIN_SCRIPT.'?op=system&act=area',
				$amessages['edit_area'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=system&act=area';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_area'] => '',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$areaInfo = $areas->getObject($id);
if(!$areaInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);

	# Allow some javascript
	$template->assign('ckEditor',1);

	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
	
			# Category combo box
			$categoryCombo = $areas->generateCombo($request->element('parent_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			
			# Shipping combo box
			$shippingCombo = $shippingMethods->generateCombo($request->element('ship_id',0));
			if($shippingCombo) $template->assign('shippingCombo',$shippingCombo);
		} else { # Valid data input
			# Category combo box
			$categoryCombo = $areas->generateCombo($request->element('aid',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			
			# Shipping combo box
			$shippingCombo = $shippingMethods->generateCombo($request->element('ship_id',0));
			if($shippingCombo) $template->assign('shippingCombo',$shippingCombo);
			
			# check duplicate category name
			if($areas->checkDuplicate($request->element('name'),'name',"`id` <> '$id'")) {
				$validate['INPUT']['name']['message'] = $amessages['name_duplicated'];
				$validate['INPUT']['name']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
			
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$data = array('store_id' => $storeId,
						      'ship_id' => Filter($request->element('ship_id')),
							  'aid' => Filter($request->element('parent_id')),
							  'name' => Filter($request->element('name')),
							  'time' => Filter($request->element('time')),
							  'price' => Filter($request->element('price')),
							  'position' => Filter($request->element('position')),
							  'status' => Filter($request->element('status')));
				$areas->updateData($data,$id);
				
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_area'],$areas->getNameFromId($id)),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
				
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=system&act=area&mod=edit&lang=$lang&id=$id&rcode=7");
			}
		}
	} else { # Load product category information to edit
		$template->assign('item',$areaInfo);
	
		# Category combo box
		$categoryCombo = $areas->generateCombo($areaInfo->getAId());
		if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		
		# Shipping combo box
		$shippingCombo = $shippingMethods->generateCombo($areaInfo->getShipId());
		if($shippingCombo) $template->assign('shippingCombo',$shippingCombo);
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
	$error['INPUT']['ship_id'] = $validate->pasteString($request->element('ship_id'));
	$error['INPUT']['time'] = $validate->pasteString($request->element('time'));
	$error['INPUT']['price'] = $validate->pasteString($request->element('price'));
	$error['INPUT']['position'] = $validate->pasteString($request->element('position'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	if($error['INPUT']['name']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>