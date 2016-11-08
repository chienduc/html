<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'newsletteremailtemplate.tpl.html';
include_once(ROOT_PATH.'classes/dao/emailtemplate.class.php');
$emailtemplate = new EmailTemplate($storeId);

$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."newsletter/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=newsletter',
				$amessages['manage_emailtemplate'] => '/'.ADMIN_SCRIPT.'?op=newsletter&act=emailtemplate',
				$amessages['add_new'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=newsletter&act=emailtemplate';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => '',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

# Allow some javascript
$template->assign('ckEditor',1);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		
	} else { # Valid data input
		if(!$validate['invalid']) {
			
			# Check if gallery folder is exists
			if(!file_exists($gallery_root)) mkdir("$gallery_root");
			if(!file_exists($gallery_path)) mkdir("$gallery_path");
			
			# Files upload
			$files = isset($_FILES['files'])?$_FILES['files']:'';
			if($files) {
				$ufiles = array();
				for($i=0; $i<count($files['name']);$i++) {
					$img = addslashes(Filter(rand()."_".$files['name'][$i]));
					$tmp_img = $files['tmp_name'][$i];
					$size = $files['size'][$i];
					$type=strtolower(substr($img,-3));
					if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($type))) {
						# Upload
						move_uploaded_file($tmp_img,$gallery_path.$img);
						$ufiles[] = $img;
					} 
				} 
			}
			$properties = array('files' => $ufiles);
			
			$data = array('store_id' => $storeId,
						  'name' => $request->element('name'),
						  'title' => $request->element('title'),
						  'content' => $request->element('detail'),
						  'properties' => serialize($properties),
						  'status' => $request->element('status'));
			$newId = $emailtemplate->addData($data);
						
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_emailtemplate'],$request->element('username')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=newsletter&act=emailtemplate&mod=list&rcode=6");
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
	$error['INPUT']['title'] = $validate->validString($request->element('title'),$amessages['title']);

	
    if($error['INPUT']['name']['error'] || $error['INPUT']['title']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>