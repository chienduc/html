<?php
/*************************************************************************
Editing article module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Last updated: 09/09/2011
Coder: Tran Thi My Xuyen
**************************************************************************/
$userInfo->checkPermission('customer','edit');
$templateFile = 'managecustomer.tpl.html';
include_once(ROOT_PATH.'classes/dao/customers.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$customers = new Customers($storeId);
$fields = new Fields($storeId);
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_customer'] => '/'.ADMIN_SCRIPT.'?op=manage&act=customer',
				$amessages['edit_customer'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=customer';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_customer'] => '',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);
$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$customerInfo = $customers->getObject($id);
if(!$customerInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='customer'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);
		$customerInfo = $customers->getObject($id);
		$template->assign('customerInfo',$customerInfo);
	} else { 
		if($request->element('password')) {
				$new_password = md5($request->element('password'));
				$cpassword = md5($request->element('cpassword'));
				if($new_password != $cpassword) { # New password is same as confirm password
					$validate['INPUT']['cpassword']['message'] = $amessages['invalid_confirm_password'];
					$validate['INPUT']['cpassword']['error'] = 1;
					$validate['invalid'] = 1;
					$template->assign('error',$validate);
				}
			}		
			# check duplicate email
			if($customers->checkDuplicate($request->element('email'),'email',"`id` <>'$id'")) {
				$validate['INPUT']['email']['message'] = $amessages['email_duplicated'];
				$validate['INPUT']['email']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
		# Everything is ok. Update data to DB
		if(!$validate['invalid']) {
			$customerInfo = $customers->getObject($id);
			if($customerInfo) {			
				#User update
				$properties = $customerInfo->getProperties();
				$properties['about'] = Filter($request->element('about'));
				if($request->element('password')) $properties['password'] = $request->element('password');
				$properties['nick_yahoo'] = Filter($request->element('nick_yahoo'));
				$properties['nick_skype'] = Filter($request->element('nick_skype'));
				# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
			   $data = array('store_id' => $storeId,
			   			  'fullname' => Filter($request->element('fullname')),
						  'address' => Filter($request->element('address')),
						  'email' => Filter($request->element('email')),
						  'tel' => Filter($request->element('tel')),
						  'properties' => serialize($properties),
						  'date_created' => date("Y-m-d H:i:s"),
						  'status' => $request->element('status'));
				if($request->element('password')) $data['password'] = md5($request->element('password'));
				$customers->updateData($data,$id);
				
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_customer'],$request->element('username')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=customer&mod=edit&lang=$lang&id=$id&rcode=7");
			}
		}
	}
} else { # Load customer information to edit
	$customerInfo = $customers->getObject($id);
	if($customerInfo) {
		$template->assign('item',$customerInfo);
	}
}
# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='customer'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['username'] = $validate->pasteString($request->element('username'));
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'));
	if($request->element('password')) $error['INPUT']['password'] = $validate->validPassword($request->element('password'));
	else $error['INPUT']['password'] = $validate->pasteString($request->element('password'));
	if($request->element('cpassword')) $error['INPUT']['cpassword'] = $validate->validPassword($request->element('cpassword'),$amessages['cpassword']);
	else $error['INPUT']['cpassword'] = $validate->pasteString($request->element('cpassword'));
	$error['INPUT']['address'] = $validate->validString($request->element('address'));
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'));
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	
	if($error['INPUT']['username']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['password']['error'] || $error['INPUT']['address']['error'] || $error['INPUT']['tel']['error']  || $error['INPUT']['email']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>