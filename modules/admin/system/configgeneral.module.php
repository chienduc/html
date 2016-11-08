<?php
/*************************************************************************
System general config module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 22/05/2012
Coder: Mai Minh
**************************************************************************/
checkPermission(array(2,3));
include_once(ROOT_PATH.'classes/dao/templates.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
$templates = new Templates();
$fields = new Fields($storeId);
$templateFile = 'systemconfig.tpl.html';

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['system'] => '/'.ADMIN_SCRIPT.'?op=system',
				$amessages['system_config'] => '/'.ADMIN_SCRIPT.'?op=system&act=config',
				$amessages['system_config_general'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=system&act=config';
$listTabs = array($amessages['general_config'] => $tabLink.'&mod=general',
				$amessages['site_down'] => $tabLink.'&mod=down');
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',1);

$gallery_root = ROOT_PATH."upload/";
$gallery_path = $gallery_root."images/";

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$error_code = $request->element('ecode');
if($error_code) $template->assign('error_code',$error_code);

# Load KFM to set Admin logo
$template->assign('selectPhoto',1);
# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='estore'",array('position' => 'ASC'));
		if($fieldList)$template->assign('fieldList',$fieldList);
		
if($_POST) { # if form is submitted
	if($request->element('doo') == 'cancel') {	# Cancel
		header('location:'.'/'.ADMIN_SCRIPT."?op=system&act=config&mod=general&lang=$lang&ecode=7");
		exit;
	}
	if($request->element('doo') == 'submit') {
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$template->assign('estore',$estore);
		} else { # Valid data input
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				
				# Check if gallery folder is exists
			    if(!file_exists($gallery_root)) mkdir("$gallery_root");
			    if(!file_exists($gallery_path)) mkdir("$gallery_path");
				
				
			
			
				$properties = $estore->getProperties();
				if(!$properties) $properties = array('');
				$properties['price_room']=$request->element('price_room');
				$properties['title_index']=$request->element('title_index');
				$properties['domain_template_id'] = $request->element('domain_template_id');
				$properties['check_duplicate_product_name'] = $request->element('check_duplicate_product_name');
				$properties['currency'] = $request->element('currency','VND');
				$properties['admin_logo'] = $request->element('admin_logo');
				
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
						  
						$properties['store_logo']='/upload/images/l_'.$new_img; 
					} 
				} #/if (preg_match
			}
			
			
				# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = $request->element($field->getName());
					}
				# End change by Thai Nguyen
				$data = array('name' => Filter($request->element('site_name')),
							  'keywords' => Filter($request->element('keywords')),
							  'description' => Filter($request->element('site_description')),
							  'company' => Filter($request->element('company')),
							  'address' => Filter($request->element('address')),
							  'tel' => Filter($request->element('tel')),
							  'cell' => Filter($request->element('cell')),
							  'email' => Filter($request->element('email')),
							  'properties' => serialize($properties));
				$stores->updateData($data,$storeId);
				$estore = $stores->getObject($storeId);
				$template->assign('item',$estore);
				
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>$amessages['tracking']['update_general_setting_ok'],'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
				# Redirect to editing page
				header('location:'.'/'.ADMIN_SCRIPT."?op=system&act=config&mod=general&lang=$lang&rcode=7");
			}
		}
	} else if($estore) $template->assign('item',$estore);
} else if($estore) $template->assign('item',$estore);

#$standardTemplates = $templates->getObjects(1,'`status` = 1 AND `owner_id` = 0','',1000);
#if($standardTemplates) $template->assign('standardTemplates',$templates->generateCombo($standardTemplates,$estore->getProperty('template_id')));
$domainTemplates = $templates->getObjects(1,'`status` = 1 AND (`owner_id` = 0 OR `owner_id` = '.$estore->getId().')','',1000);
if($domainTemplates) $template->assign('domainTemplates',$templates->generateCombo($domainTemplates,$estore->getProperty('domain_template_id')));

function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['site_name'] = $validate->validString($request->element('site_name'),$amessages['site_name']);
	$error['INPUT']['keywords'] = $validate->validString($request->element('keywords'),$amessages['site_keywords']);
	$error['INPUT']['site_description'] = $validate->validString($request->element('site_description'),$amessages['site_description']);
	$error['INPUT']['company'] = $validate->pasteString($request->element('company'));
	$error['INPUT']['address'] = $validate->pasteString($request->element('address'));
	$error['INPUT']['email'] = $validate->pasteString($request->element('email'));
	$error['INPUT']['tel'] = $validate->pasteString($request->element('tel'));
	$error['INPUT']['cell'] = $validate->pasteString($request->element('cell'));
	$error['INPUT']['allow_duplicate_product_name'] = $validate->pasteString($request->element('allow_duplicate_product_name'));
	$error['INPUT']['admin_logo'] = $validate->pasteString($request->element('admin_logo'));
	$error['INPUT']['store_logo'] = $validate->pasteString($request->element('store_logo'));			
	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
		if($field->getType() == 4 || $field->getType() == 7) {	# Listbox and checkbox
			$error['INPUT'][$field->getName()]['value'] = $request->element($field->getName());
		}
	}
	if($error['INPUT']['site_name']['error'] || $error['INPUT']['keywords']['error'] || $error['INPUT']['site_description']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>