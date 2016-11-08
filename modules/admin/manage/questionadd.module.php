<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'managequestion.tpl.html';
include_once(ROOT_PATH.'classes/dao/questions.class.php');
include_once(ROOT_PATH.'classes/dao/servicecategories.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$questions = new Questions();
$fields = new Fields($storeId);
$serviceCategories = new ServiceCategories($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."questions/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_question'] => '/'.ADMIN_SCRIPT.'?op=manage&act=question',
				$amessages['add_new_question'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=question';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => $tabLink.'&mod=add',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

# Category combo box
$categoryCombo = $serviceCategories->generateCombo($request->element('cat_id'),0);
if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

# Get list of custom fields
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='question'",array('position' => 'ASC'));
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
			$dup = $questions->checkDuplicate($slug.($i?'-'.$i:''),'slug');
			if($dup) $i++;
		}
		$slug .= $i?'-'.$i:'';
		
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
		  
            	# Check if gallery folder is exists
			if(!file_exists($gallery_root)) mkdir("$gallery_root");
			if(!file_exists($gallery_path)) mkdir("$gallery_path");
            
            
			$properties = array('');
            
            	# File Avatar
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
						resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_THUMBNAIL_SIZE,DEFAULT_THUMBNAIL_SQUARE,DEFAULT_PHOTO_QUALITY);
						if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
						$avatar = $new_img;
					} 
				} #/if (preg_match
			}
  	       $properties = array('avatar' => $avatar);
            
			
			# Custom fields
			foreach($fieldList as $field) {
				$properties[$field->getName()] = stripslashes($request->element($field->getName()));
			}
			
			# End File upload
			$data = array('name' => Filter($request->element('name')),
						  'slug' => $slug,
						  'title' => Filter($request->element('title')),
                          'sapo' => Filter($request->element('sapo')),
						  'email' => $request->element('email'),
						  'id_service' => $request->element('cat_id'),
						  'tel' => $request->element('tel'),
						  'answer' => $request->element('detail'),
						  'position' => $request->element('position'),
						  'status' => $request->element('status'),
						  'properties' => serialize($properties),
						  'created' => date("Y-m-d H:i:s"));
			$newId = $questions->addData($data);
					
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_question'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=question&mod=list&pId=".$request->element('cat_id')."&rcode=6");
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