<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/

$templateFile = 'managetypetour.tpl.html';
include_once(ROOT_PATH.'classes/dao/typetours.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$typetours = new Typetours();
$fields = new Fields($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."typetours/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_typetour'] => '/'.ADMIN_SCRIPT.'?op=manage&act=typetour',
				$amessages['edit_typetour'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=typetour';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_typetour'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$typetourInfo = $typetours->getObject($id);



if(!$typetourInfo) {
	$template->assign('validItem',0);
} else{
	$template->assign('validItem',1);
    
   	if($request->element('doo') == 'delAvatar') {
		$typetourInfo = $typetours->getObject($id);
		$properties = $typetourInfo->getProperties();
		if($typetourInfo->getProperty('avatar')) {
			unlink($gallery_path.$typetourInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$typetours->updateData($data,$id);
			$typetourInfo = $typetours->getObject($id);
		}
	}

	# Allow some javascript
	$template->assign('ckEditor',1);
	

	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='typetour'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$typetourInfo = $typetours->getObject($id);
			$template->assign('itemInfo',$typetourInfo);
			
			
			
		} else { # Valid data input
			
			
			# Check if duplicate slug
			
			$slug = TextFilter::urlize($request->element('slug'),false,'-');
			$i = 0;
			$dup = 1;
			while($dup) {
				$dup = $typetours->checkDuplicate($slug.($i?'-'.$i:''),'slug',"`id` <> '$id'");
				if($dup) $i++;
			}
			$slug .= $i?'-'.$i:'';
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$typetourInfo = $typetours->getObject($id); 
				if($typetourInfo) {			
					
					
					
			
					$data = array(
								  'slug' => $slug,
								  'title' => Filter($request->element('title')),
								  'position' => $request->element('position'),
								  'status' => $request->element('status'));
					$typetours->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_typetour'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
                   
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=typetour&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load typetour category information to edit
		$template->assign('item',$typetourInfo);
		
		
		
		
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='typetour'",array('position' => 'ASC'));
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
	
	$error['INPUT']['position'] = $validate->pasteString($request->element('position'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
		
	if($error['INPUT']['title']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>