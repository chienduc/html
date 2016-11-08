<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'managecontact.tpl.html';
include_once(ROOT_PATH.'classes/dao/contacts.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$contacts = new Contacts();
$fields = new Fields($storeId);
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_contact'] => '/'.ADMIN_SCRIPT.'?op=manage&act=contact',
				$amessages['add_new_contact'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=contact';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => $tabLink.'&mod=add',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

# Get list of custom fields
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='contact'",array('position' => 'ASC'));
if($fieldList) $template->assign('fieldList',$fieldList);

# Allow some javascript
$template->assign('ckEditor',1);

if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
	} else { # Valid data input
		# Check if duplicate slug
		$slug = TextFilter::urlize($request->element('title'),false,'-');
		$i = 0;
		$dup = 1;
		while($dup) {
			$dup = $contacts->checkDuplicate($slug.($i?'-'.$i:''),'slug');
			if($dup) $i++;
		}
		$slug .= $i?'-'.$i:'';
		
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			$properties = array('');
			
			# Custom fields
			foreach($fieldList as $field) {
				$properties[$field->getName()] = stripslashes($request->element($field->getName()));
			}
			
			# End File upload
			$data = array('name' => Filter($request->element('name')),
						  'slug' => $slug,
						  'title' => Filter($request->element('title')),
						  'address' => Filter($request->element('address')),
						  'email' => $request->element('email'),
						  'tel' => $request->element('tel'),
						  'fax' => $request->element('fax'),
						  'ip' => $request->element('ip'),
						  'detail' => $request->element('detail'),
						  'status' => $request->element('status'),
						  'properties' => serialize($properties));
			$newId = $contacts->addData($data);
					
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_contact'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=contact&mod=list&pId=".$request->element('cat_id')."&rcode=6");
		}
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['title'] = $validate->validString($request->element('title'),$amessages['title']);
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$amessages['address']);
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'),$amessages['email']);
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'),$amessages['telephone']);
	$error['INPUT']['fax'] = $validate->validString($request->element('fax'),$amessages['fax']);
	$error['INPUT']['ip'] = $validate->validString($request->element('ip'));
	$error['INPUT']['detail'] = $validate->validString($request->element('detail'),$amessages['detail']);

	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		
	}
		
	if($error['INPUT']['title']['error'] || $error['INPUT']['name']['error'] || $error['INPUT']['email']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>