<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'manageclients.tpl.html';
include_once(ROOT_PATH.'classes/dao/clients.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/fields.class.php');
$clients = new Clients($storeId);
$fields = new Fields($storeId);
$gallery_path = ROOT_PATH."upload/1/clients/";
$gallery_avatar  = $gallery_path.'avatar/';
$gallery_pdf  = $gallery_path."pdf/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_clients'] => '/'.ADMIN_SCRIPT.'?op=manage&act=clients',
				$amessages['add_new'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=clients';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

# Get list of custom fields
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='clients'",array('position' => 'ASC'));
if($fieldList) $template->assign('fieldList',$fieldList);


$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);


if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
	} else { # Valid data input
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			$properties = array('');
			# Check if gallery folder is exists
			if(!file_exists($gallery_path)) mkdir("$gallery_path");
			if(!file_exists($gallery_avatar)) mkdir("$gallery_avatar");
			if(!file_exists($gallery_pdf)) mkdir("$gallery_pdf");
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
						move_uploaded_file($tmp_img,$gallery_avatar.'l_'.$img);
						if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
						resize($gallery_avatar,$gallery_avatar,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
						resize($gallery_avatar,$gallery_avatar,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
						if($img != $new_img) unlink($gallery_avatar,'l_'.$img);
						$avatar = $new_img;
					} 
				} #/if (preg_match
			}
			
			# Files upload
			$files = isset($_FILES['files'])?$_FILES['files']:'';
			if($files) {
					$img = addslashes(Filter(rand()."_".$files['name']));
					$tmp_img = $files['tmp_name'];
					$size = $files['size'];
					$type=strtolower(substr($img,-3));
					if(preg_match("/".ALLOW_FILE_PDF."/",strtolower($img))) {
						# Upload
							move_uploaded_file($tmp_img,$gallery_pdf.$img);
							$pdf = $img;
						
					} 
			}
			
		    $properties = array('avatar' => $avatar,
			                    'pdf' => $pdf);
			# Custom fields
			foreach($fieldList as $field) {
				$properties[$field->getName()] = stripslashes($request->element($field->getName()));
			}
			
			$data = array('store_id' => $storeId,
						  'name' => Filter($request->element('name')),
						  'position' => $request->element('position'),
						  'status' => $request->element('status'),
						  'properties' => serialize($properties),
						  'date_created' => date("Y-m-d H:i:s"));
			$newId = $clients->addData($data);
				
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_clients'],$newId),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=clients&mod=list&wid=".$request->element('wid')."&rcode=6");
		}
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['position'] = $validate->pasteString($request->element('position'));
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
	}
	if( $error['INPUT']['name']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}

?>