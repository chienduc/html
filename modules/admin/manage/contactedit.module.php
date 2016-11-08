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
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."contacts/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_contact'] => '/'.ADMIN_SCRIPT.'?op=manage&act=contact',
				$amessages['edit_contact'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=contact';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_contact'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$contactInfo = $contacts->getObject($id);
if(!$contactInfo) {
	$template->assign('validItem',0);
} else{
	$template->assign('validItem',1);

	# Allow some javascript
	$template->assign('ckEditor',1);
	
	if($request->element('doo') == 'delPhoto') { 
		$contactInfo = $contacts->getObject($id);
		$properties = $contactInfo->getProperties();
		foreach($properties['photos'] as $key => $value) {
			if($value == $request->element('photo')) {
				unlink($gallery_path."l_".$properties['photos'][$key]);
				unlink($gallery_path.preg_replace("/(png$|bmp$|gif$)/","jpg",$properties['photos'][$key]));
				unset($properties['photos'][$key]);
				$data = array('properties' => serialize($properties));
				$contacts->updateData($data,$id);
				$contactInfo = $contacts->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delAvatar') {
		$contactInfo = $contacts->getObject($id);
		$properties = $contactInfo->getProperties();
		if($contactInfo->getProperty('avatar')) {
			unlink($gallery_path.$contactInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$contacts->updateData($data,$id);
			$contactInfo = $contacts->getObject($id);
		}
	}	
	if($request->element('doo') == 'delMovie') {
		$contactInfo = $contacts->getObject($id);
		$properties = $contactInfo->getProperties();
		foreach($properties['movies'] as $key => $value) {
			if($value == $request->element('movie')) {
				unlink($gallery_path.$properties['movies'][$key]);
				unset($properties['movies'][$key]);
				$data = array('properties' => serialize($properties));
				$contacts->updateData($data,$id);
				$contactInfo = $contacts->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delFile') {
		$contactInfo = $contacts->getObject($id);
		$properties = $contactInfo->getProperties();
		foreach($properties['files'] as $key => $value) {
			if($value == $request->element('file')) {
				unlink($gallery_path.$properties['files'][$key]);
				unset($properties['files'][$key]);
				$data = array('properties' => serialize($properties));
				$contacts->updateData($data,$id);
				$contactInfo = $contacts->getObject($id);
				break;
			}
		}
	}
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='contact'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$contactInfo = $contacts->getObject($id);
			$template->assign('itemInfo',$contactInfo);
			
		} else { # Valid data input
			# Check if duplicate slug
			$slug = TextFilter::urlize($request->element('slug'),false,'-');
			$i = 0;
			$dup = 1;
			while($dup) {
				$dup = $contacts->checkDuplicate($slug.($i?'-'.$i:''),'slug',"`id` <> '$id' AND `cat_id` = '".$request->element('cat_id')."'");
				if($dup) $i++;
			}
			$slug .= $i?'-'.$i:'';
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$contactInfo = $contacts->getObject($id);
				if($contactInfo) {			
					$properties = $contactInfo->getProperties();
				
					#User update
					$properties['user_update'] = $userInfo->getUsername();
					
					
					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
			
					$data = array('name' => Filter($request->element('name')),
								  'slug' => $slug,
								  'title' => Filter($request->element('title')),
								  'email' => $request->element('email'),
								  'address' => $request->element('address'),
								  'tel'   => $request->element('tel'),
								  'fax'   => $request->element('fax'),
								  'ip'   => $request->element('ip'),
								  'detail' => $request->element('detail'),
								  'status' => $request->element('status'),
								  'properties' => serialize($properties));
					$contacts->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_contact'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=contact&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load contact category information to edit
		$template->assign('item',$contactInfo);
		
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='contact'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
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