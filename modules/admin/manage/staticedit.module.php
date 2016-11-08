<?php
/*************************************************************************
Editing static module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd
Last updated: 15/09/2011
Coder: Tran Thi My Xuyen
Checked by: Mai Minh (07/05/2012)
**************************************************************************/
$userInfo->checkPermission('static','edit');
$templateFile = 'managestatic.tpl.html';
include_once(ROOT_PATH.'classes/dao/static.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$statics = new StaticPage($storeId);
$fields = new Fields($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."resources/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_static'] => '/'.ADMIN_SCRIPT.'?op=manage&act=static',
				$amessages['edit_static'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=static';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_static'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$staticInfo = $statics->getObject($id);
if(!$staticInfo) {	
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	# Allow some javascript
	$template->assign('ckEditor',1);
	
	if($request->element('doo') == 'delPhoto') { 
		$staticInfo = $statics->getObject($id);
		$properties = $staticInfo->getProperties();
		foreach($properties['photos'] as $key => $value) {
			if($value == $request->element('photo')) {
				unlink($gallery_path."l_".$properties['photos'][$key]);
				unlink($gallery_path."a_".preg_replace("/(png$|bmp$|gif$)/","jpg",$properties['photos'][$key]));
				unset($properties['photos'][$key]);
				$data = array('properties' => serialize($properties));
				$statics->updateData($data,$id);
				$staticInfo = $statics->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delMovie') {
		$staticInfo = $statics->getObject($id);
		$properties = $staticInfo->getProperties();
		foreach($properties['movies'] as $key => $value) {
			if($value == $request->element('movie')) {
				unlink($gallery_path.$properties['movies'][$key]);
				unset($properties['movies'][$key]);
				$data = array('properties' => serialize($properties));
				$statics->updateData($data,$id);
				$staticInfo = $statics->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delFile') {
		$staticInfo = $statics->getObject($id);
		$properties = $staticInfo->getProperties();
		foreach($properties['files'] as $key => $value) {
			if($value == $request->element('file')) {
				unlink($gallery_path.$properties['files'][$key]);
				unset($properties['files'][$key]);
				$data = array('properties' => serialize($properties));
				$statics->updateData($data,$id);
				$staticInfo = $statics->getObject($id);
				break;
			}
		}
	}
	
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='static'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$staticInfo = $statics->getObject($id);
			$template->assign('itemInfo',$staticInfo);
			
		} else { # Valid data input
			# Check if duplicate slug
			$slug = TextFilter::urlize($request->element('slug'),false,'-');
			$i = 0;
			$dup = 1;
			while($dup) {
				$dup = $statics->checkDuplicate($slug.($i?'-'.$i:''),'slug',"`id` <> '$id' ");
				if($dup) $i++;
			}
			$slug .= $i?'-'.$i:'';
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$staticInfo = $statics->getObject($id);
				if($staticInfo) {			
					$properties = $staticInfo->getProperties();
					
					# Check if gallery folder is exists
					if(!file_exists($gallery_root)) mkdir("$gallery_root");
					if(!file_exists($gallery_path)) mkdir("$gallery_path");
										
					#User update
					$properties['user_update'] = $userInfo->getUsername();
					# Files upload
					$files = isset($_FILES['files'])?$_FILES['files']:'';
					if($files) {
						if(!isset($properties['photos'])) $properties['photos'] = array();
						if(!isset($properties['videos'])) $properties['videos'] = array();
						if(!isset($properties['files'])) $properties['files'] = array();
						for($i=0; $i<count($files['name']);$i++) {
							$img = addslashes(Filter(rand()."_".$files['name'][$i]));
							$tmp_img = $files['tmp_name'][$i];
							$size = $files['size'][$i];
							$type=strtolower(substr($img,-3));
							if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
								# Upload
								if(isImage($img)) {
									$new_img = $img;
									move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
									if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
									resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);									
									//if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
									$properties['photos'][] = $img;
								} elseif(isVideo($img)) {
									move_uploaded_file($tmp_img,$gallery_path.$img);
									$properties['videos'][] = $img;
								} else {
									move_uploaded_file($tmp_img,$gallery_path.$img);
									$properties['files'][] = $img;
								}
							} #/if (preg_match
						} #/for($i=0;
					}
					# End File upload
					
					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
					
					$data = array('store_id' => $storeId,
								  'slug' => $slug,
								  'keywords' => Filter($request->element('keywords')),
								  'title' => Filter($request->element('title')),
								  'sapo' => Filter($request->element('sapo')),
								  'details' => $request->element('detail'),
								  'status' => $request->element('status'),
								  'properties' => serialize($properties),
								  'date_created' => date("Y-m-d H:i:s"));
					$statics->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_article'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=static&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load product category information to edit
			$template->assign('item',$staticInfo);
			
			# Get list of custom fields
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='static'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['slug'] = $validate->validString($request->element('slug'),$amessages['slug']);
	$error['INPUT']['title'] = $validate->validString($request->element('title'),$amessages['title']);
	$error['INPUT']['keywords'] = $validate->validString($request->element('keywords'),$amessages['keyword']);
	$error['INPUT']['sapo'] = $validate->validString($request->element('sapo'),$amessages['sapo']);
	$error['INPUT']['detail'] = $validate->validString($request->element('detail'),$amessages['detail']);
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	
	if($error['INPUT']['title']['error'] || $error['INPUT']['keywords']['error'] || $error['INPUT']['slug']['error'] || $error['INPUT']['sapo']['error'] || $error['INPUT']['detail']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>