<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'manageactivities.tpl.html';
include_once(ROOT_PATH.'classes/dao/activitiescategories.class.php');
include_once(ROOT_PATH.'classes/dao/activities.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$activitiesCategories = new ActivitiesCategories($storeId);
$activities = new Activities($storeId);
$gallery_path = ROOT_PATH."upload/1/activities/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_activities'] => '/'.ADMIN_SCRIPT.'?op=manage&act=activities',
				$amessages['add_new'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=activities';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => '#',
				$amessages['list_activities_category'] => $tabLink.'&mod=listcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
# Category combo box
$categoryCombo = $activitiesCategories->generateCombo($request->element('gId'),1);  
if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);

if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		# Category combo box
		$categoryCombo = $activitiesCategories->generateCombo($request->element('wid'),1);
		if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
	} else { # Valid data input
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			$properties = array('');
			# Check if gallery folder is exists
			if(!file_exists($gallery_path)) mkdir("$gallery_path");
			# Files upload
			$files = isset($_FILES['files'])?$_FILES['files']:'';
			if($files) {
						if(!isset($properties['avatar'])) $properties['avatar'] = array();
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
									resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,150,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
									$properties['avatar']= $new_img;
									
			                      $data = array('store_id' => $storeId,
						                        'wid' => $request->element('wid'),
						                        'name' => $new_img,
						                        'position' =>rand(1,100),
						                        'status' => 1,
						                        'properties' => serialize($properties),
						                        'date_created' => date("Y-m-d H:i:s"));
		                          $newId = $activities->addData($data);
								
								} 
							}
						}
					}
			
				
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_activities'],$newId),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=activities&mod=list&wid=".$request->element('wid')."&rcode=6");
		}
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['wid'] = $validate->pasteString($request->element('wid'));

	if( $error['INPUT']['wid']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}

?>