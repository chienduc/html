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
				$amessages['edit_emailtemplate'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=newsletter&act=emailtemplate';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_emailtemplate'] => '',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);
$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);

# Allow some javascript
$template->assign('ckEditor',1);

$id = $request->element('id');
if($id) $template->assign('id',$id);
$emailtemplateInfo = $emailtemplate->getObject($id);
if(!$emailtemplateInfo) {
	$template->assign('validItem',0);
} else {
	$template->assign('validItem',1);
	
	if($request->element('doo') == 'delFile') {
		$emailtemplateInfo = $emailtemplate->getObject($id);
		$properties = $emailtemplateInfo->getProperties();
		foreach($properties['files'] as $key => $value) {
			if($value == $request->element('file')) {
				
				unlink($gallery_path.$properties['files'][$key]);
				unset($properties['files'][$key]);
				$data = array('properties' => serialize($properties));
				$emailtemplate->updateData($data,$id);
				$emailtemplateInfo = $emailtemplate->getObject($id);
				break;
			}
		}
	}
    if($_POST && $request->element('doo') == 'submit') { # if	
	
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);
		$emailtemplateInfo = $emailtemplate->getObject($id);
		$template->assign('emailtemplateInfo',$emailtemplateInfo);
	} else { 
	         $properties = $emailtemplateInfo->getProperties();
			 # Check if gallery folder is exists
			 if(!file_exists($gallery_root)) mkdir("$gallery_root");
			 if(!file_exists($gallery_path)) mkdir("$gallery_path");
			 # Files upload
			 $files = isset($_FILES['files'])?$_FILES['files']:'';
					if($files) {
						if(!isset($properties['files'])) $properties['files'] = array();
						for($i=0; $i<count($files['name']);$i++) {
							$img = addslashes(Filter(rand()."_".$files['name'][$i]));
							$tmp_img = $files['tmp_name'][$i];
							$size = $files['size'][$i];
							$type=strtolower(substr($img,-3));
							if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
					         move_uploaded_file($tmp_img,$gallery_path.$img);
							 $properties['files'][] = $img;
								
							} #/if (preg_match
						} #/for($i=0;
					}
			  $data = array('store_id' => $storeId,
						    'name' => $request->element('name'),
						    'title' => $request->element('title'),
						    'content' => $request->element('detail'),
						    'properties' => serialize($properties),
						    'status' => $request->element('status'));
				$emailtemplate->updateData($data,$id);
				
				
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_emailtemplate'],$request->element('username')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=newsletter&act=emailtemplate&mod=edit&lang=$lang&id=$id&rcode=7");
			
		
	}
} else { # Load emailtemplate information to edit
	   $template->assign('item',$emailtemplateInfo);
	  }
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['name'] = $validate->validString($request->element('name'));
	$error['INPUT']['title'] = $validate->validString($request->element('title'));
	
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));
	if($error['INPUT']['name']['error'] || $error['INPUT']['title']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>