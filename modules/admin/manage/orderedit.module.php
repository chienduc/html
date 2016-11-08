<?php
/*************************************************************************
Editing article module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Last updated: 09/09/2011
Coder: Tran Thi My Xuyen
**************************************************************************/
$userInfo->checkPermission('order','edit');
$templateFile = 'manageorderedit.tpl.html';
include_once(ROOT_PATH.'classes/dao/orders.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$orders = new Orders($storeId);
$fields = new Fields($storeId);
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_customer'] => '/'.ADMIN_SCRIPT.'?op=manage&act=order',
				$amessages['edit_order'] => '');
$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$orderInfo = $orders->getObject($id);
if(!$orderInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='order'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);
		$orderInfo = $orders->getObject($id);
		$template->assign('orderInfo',$orderInfo);
	} else { 
		
		# Everything is ok. Update data to DB
		if(!$validate['invalid']) {
			$orderInfo = $orders->getObject($id);
			if($orderInfo) {			
				#User update
				$properties = $orderInfo->getProperties();
				$properties['rnote'] = Filter($request->element('rnote'));
				$properties['user_update'] = $userInfo->getUsername();
				# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
               $statusOrder = $request->element('status');
			   $data = array('store_id' => $storeId,
			   			  'name' => Filter($request->element('name')),
						  'address' => Filter($request->element('address')),
						  'email' => Filter($request->element('email')),
						  'cell' => Filter($request->element('cell')),
						  'tel' => Filter($request->element('tel')),
						  'rname' => Filter($request->element('rname')),
						  'raddress' => Filter($request->element('raddress')),
						  'rcell' => Filter($request->element('rcell')),
						  'rtel' => Filter($request->element('rtel')), 
						  'properties' => serialize($properties),
						  'updated' => date("Y-m-d H:i:s"),
						  'status' => $statusOrder);
				$orders->updateData($data,$id);
				
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_order'],$id),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			     if($statusOrder == 5){
			         #Call to ORDERNEW addon
                    foreach($addons->getAddonFromEvent('ORDER_PAID') as $addon) {include_once(ROOT_PATH."addons/$addon/addon.php");}
			     }
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=order&mod=edit&lang=$lang&id=$id&rcode=7");
			}
		}
	}
} else { # Load customer information to edit
	$orderInfo = $orders->getObject($id);
	if($orderInfo) {
		$template->assign('item',$orderInfo);
	}
	
}
# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='order'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	$error['INPUT']['name'] = $validate->pasteString($request->element('name'));
	$error['INPUT']['address'] = $validate->pasteString($request->element('address'));
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'));
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'));
	$error['INPUT']['cell'] = $validate->pasteString($request->element('cell'));
	$error['INPUT']['rname'] = $validate->pasteString($request->element('rname'));
	$error['INPUT']['raddress'] = $validate->pasteString($request->element('raddress'));
	$error['INPUT']['rtel'] = $validate->validString($request->element('rtel'));
	$error['INPUT']['rcell'] = $validate->pasteString($request->element('rcell'));
	$error['INPUT']['rnote'] = $validate->pasteString($request->element('rnote'));
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	if($error['INPUT']['name']['error'] || $error['INPUT']['address']['error'] || $error['INPUT']['email']['error'] || $error['INPUT']['tel']['error'] || $error['INPUT']['rname']['error']  || $error['INPUT']['raddress']['error'] || $error['INPUT']['rtel']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>