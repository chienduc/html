<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/

$templateFile = 'managetypedate.tpl.html';
include_once(ROOT_PATH.'classes/dao/typedates.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$typedates = new Typedates();
$fields = new Fields($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."typedates/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_typedate'] => '/'.ADMIN_SCRIPT.'?op=manage&act=typedate',
				$amessages['edit_typedate'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=typedate';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_typedate'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$typedateInfo = $typedates->getObject($id);

if(!$typedateInfo) {
	$template->assign('validItem',0);
} else{
	$template->assign('validItem',1);
 
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='typedate'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$typedateInfo = $typedates->getObject($id);
			$template->assign('itemInfo',$typedateInfo);
			
			
			
		} else { # Valid data input
			
			
			
			# Check if duplicate slug
			
			
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$typedateInfo = $typedates->getObject($id); 
				if($typedateInfo) {			
					
			
					$data = array(
								  'title' => Filter($request->element('title')),
								  'position' => $request->element('position'),
								  'status' => $request->element('status'));
					$typedates->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_typedate'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
                   
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=typedate&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load typedate category information to edit
		$template->assign('item',$typedateInfo);
		
		
		
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='typedate'",array('position' => 'ASC'));
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
	
		
	if($error['INPUT']['title']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>