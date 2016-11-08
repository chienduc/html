<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/

$templateFile = 'managequestion.tpl.html';
include_once(ROOT_PATH.'classes/dao/questions.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/servicecategories.class.php');
$questions = new Questions();
$fields = new Fields($storeId);
$serviceCategories = new ServiceCategories($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."questions/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_question'] => '/'.ADMIN_SCRIPT.'?op=manage&act=question',
				$amessages['edit_question'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=question';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_question'] => '#',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$questionInfo = $questions->getObject($id);

if(!$questionInfo) {
	$template->assign('validItem',0);
} else{
	$template->assign('validItem',1);
    
   	if($request->element('doo') == 'delAvatar') {
		$questionInfo = $questions->getObject($id);
		$properties = $questionInfo->getProperties();
		if($questionInfo->getProperty('avatar')) {
			unlink($gallery_path.$questionInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$questions->updateData($data,$id);
			$questionInfo = $questions->getObject($id);
		}
	}

	# Allow some javascript
	$template->assign('ckEditor',1);
	
	if($request->element('doo') == 'delPhoto') { 
		$questionInfo = $questions->getObject($id);
		$properties = $questionInfo->getProperties();
		foreach($properties['photos'] as $key => $value) {
			if($value == $request->element('photo')) {
				unlink($gallery_path."l_".$properties['photos'][$key]);
				unlink($gallery_path.preg_replace("/(png$|bmp$|gif$)/","jpg",$properties['photos'][$key]));
				unset($properties['photos'][$key]);
				$data = array('properties' => serialize($properties));
				$questions->updateData($data,$id);
				$questionInfo = $questions->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delAvatar') {
		$questionInfo = $questions->getObject($id);
		$properties = $questionInfo->getProperties();
		if($questionInfo->getProperty('avatar')) {
			unlink($gallery_path.$questionInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$questions->updateData($data,$id);
			$questionInfo = $questions->getObject($id);
		}
	}	
	if($request->element('doo') == 'delMovie') {
		$questionInfo = $questions->getObject($id);
		$properties = $questionInfo->getProperties();
		foreach($properties['movies'] as $key => $value) {
			if($value == $request->element('movie')) {
				unlink($gallery_path.$properties['movies'][$key]);
				unset($properties['movies'][$key]);
				$data = array('properties' => serialize($properties));
				$questions->updateData($data,$id);
				$questionInfo = $questions->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delFile') {
		$questionInfo = $questions->getObject($id);
		$properties = $questionInfo->getProperties();
		foreach($properties['files'] as $key => $value) {
			if($value == $request->element('file')) {
				unlink($gallery_path.$properties['files'][$key]);
				unset($properties['files'][$key]);
				$data = array('properties' => serialize($properties));
				$questions->updateData($data,$id);
				$questionInfo = $questions->getObject($id);
				break;
			}
		}
	}
	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='question'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$questionInfo = $questions->getObject($id);
			$template->assign('itemInfo',$questionInfo);
			
			# Category combo box
			$categoryCombo = $serviceCategories->generateCombo($request->element('cat_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			
		} else { # Valid data input
			
			# Category combo box
			$categoryCombo = $serviceCategories->generateCombo($request->element('cat_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			
			# Check if duplicate slug
			
			$slug = TextFilter::urlize($request->element('slug'),false,'-');
			$i = 0;
			$dup = 1;
			while($dup) {
				$dup = $questions->checkDuplicate($slug.($i?'-'.$i:''),'slug',"`id` <> '$id'");
				if($dup) $i++;
			}
			$slug .= $i?'-'.$i:'';
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$questionInfo = $questions->getObject($id); 
				if($questionInfo) {			
					$properties = $questionInfo->getProperties();
                    	
					# Check if gallery folder is exists
					if(!file_exists($gallery_root)) mkdir("$gallery_root");
					if(!file_exists($gallery_path)) mkdir("$gallery_path");
				
					#User update
					$properties['user_update'] = $userInfo->getUsername();
                    
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
								move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
								if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
								resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
								resize($gallery_path,$gallery_path,'l_'.$img,'t_'.$new_img,DEFAULT_THUMBNAIL_SIZE,DEFAULT_THUMBNAIL_SQUARE,DEFAULT_PHOTO_QUALITY);
								if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
								if($questionInfo->getProperty('avatar')) {
									unlink($gallery_path.$questionInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
							} 
						} #/if (preg_match
					}
					
					
					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
			
					$data = array('name' => Filter($request->element('name')),
								  'slug' => $slug,
								  'title' => Filter($request->element('title')),
                                  'sapo' => Filter($request->element('sapo')),
								  'email' => $request->element('email'),
								  'tel'   => $request->element('tel'),
								  'id_service'   => $request->element('cat_id'),
								  'answer' => $request->element('detail'),
								  'position' => $request->element('position'),
								  'status' => $request->element('status'),
								  'properties' => serialize($properties));
					$questions->updateData($data,$id);
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_question'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
                   
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=question&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load question category information to edit
		$template->assign('item',$questionInfo);
		
		# Category combo box
		$categoryCombo = $serviceCategories->generateCombo($questionInfo->getIdService());
		if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		
		
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='question'",array('position' => 'ASC'));
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
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'),$amessages['email']);
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'),$amessages['telephone']);
	$error['INPUT']['detail'] = $validate->validString($request->element('detail'),$amessages['detail']);
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
		
	if($error['INPUT']['title']['error'] || $error['INPUT']['name']['error'] || $error['INPUT']['email']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>