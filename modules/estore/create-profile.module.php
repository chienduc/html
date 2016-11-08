<?php
/*************************************************************************
Estore register module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                  
**************************************************************************/
include_once(ROOT_PATH."classes/dao/provinces.class.php");
include_once(ROOT_PATH.'classes/dao/emails.class.php');
$templateFile = "create-profile.tpl.html";
$provinces = new Provinces();
$emails = new Emails($sId);
include_once(ROOT_PATH."classes/dao/userprofiles.class.php");
$userProfiles = new UserProfiles($sId);
# Page title, keywords, description
$pageTitle = $messages['update_profile'];

if($customerInfo){
# Page title, keywords, description
if($customerInfo->getType() == 1)	$pageTitle = $messages['update_profile'];
elseif($customerInfo->getType() == 2)	$pageTitle = $messages['create_employment'];

$profileInfo = $userProfiles->getObject($customerInfo->getId(),'user_id');

if($profileInfo)	$template->assign('profileInfo',$profileInfo);
$plus = $request->element('plus');
# Get Register
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$messages["result_code"][$result_code]);
# At the beginning
$infoClass = "infoText";
$template->assign("infoClass",$infoClass);
if($profileInfo){
$provincesList = $provinces->createComboBox($profileInfo->getPlaceWork(),'id','name');
if($provincesList) $template->assign('provincesList',$provincesList);
$jobCate = $request->element('select_category',$profileInfo->getSelectCategory());
$modeWork = $request->element('mode_work',$profileInfo->getModeWork());
}else{
$provincesList = $provinces->createComboBox();
if($provincesList) $template->assign('provincesList',$provincesList);
$jobCate = $request->element('select_category','');
$modeWork = $request->element('mode_work','');
}
$listCategoryJobs = optionCategoryJobs($jobCate);
if($listCategoryJobs) $template->assign('listCategoryJobs',$listCategoryJobs);

$listModeWorks = optionModeWorks($modeWork);
if($listModeWorks) $template->assign('listModeWorks',$listModeWorks);

if($_POST){	
	switch($plus) {
		case 'ntd':
			# Validate the data input
			$validate = validateData($request);
			if($validate['invalid']) {	# data input is not in valid form
				$template->assign('error',$validate);	
				$template->assign("infoClass","errorBk");
				$template->assign('note',$messages["register_error"]);
			} else { # Valid data input
				# Everything is ok. Add data to DB
						#Property 
						$properties = array('');
						$properties = array(
											'request_work' => $request->element('request_work')
											);
						# End property
						$data = array('store_id' => $storeId,
									  'user_id'  => $customerInfo->getId(),
									  'type'	=> 2,
									  'education_level' => $request->element('education_level'),
									  'select_category' => $request->element('select_category'),
									  'place_work' => $request->element('place_work'),
									  'mode_work' => $request->element('mode_work'),
									  'salary' => $request->element('salary_desired'),
									  'properties' => serialize($properties),
									  'date_created' => date("Y-m-d H:i:s"),
									  'status' => 0);
						$newId = $userProfiles->addData($data);
						$template->assign("infoClass","infoBk");
						unset($_SESSION['rand_code']);
						header('location:/index.php?op=estore&act=create-profile&plus=ntd&rcode=5');
					
			}
		break;
		case 'ntv':
			
			# Validate the data input
			$validate = validateDataUser($request);
			if($validate['invalid']) {	# data input is not in valid form
				$template->assign('error',$validate);	
				$template->assign("infoClass","errorBk");
				$template->assign('note',$messages["register_error"]);
			} else { # Valid data input
				# Everything is ok. Add data to DB
					$check = $userProfiles->checkDuplicate($customerInfo->getId(),'user_id',"`type` = 1");
					if($check == 1){
						$property = $profileInfo->getProperties();
						$properties['foreign_language'] = $request->element('foreign_language');
						$properties['level_computer'] = $request->element('level_computer');
						$properties['degree_certificates'] = $request->element('degree_certificates');
						$properties['work_process'] = $request->element('work_process');
						$properties['outstanding_skills'] = $request->element('outstanding_skills');
						$properties['career_goals'] = $request->element('career_goals');
						# End property
						$data = array('store_id' => $storeId,
									  'user_id'  => $customerInfo->getId(),
									  'type'	=> 1,
									  'education_level' => $request->element('education_level'),
									  'job_category' => $request->element('job_category'),
									  'years_experience' => $request->element('years_experience'),
									  'select_category' => $request->element('select_category'),
									  'place_work' => $request->element('place_work'),
									  'mode_work' => $request->element('mode_work'),
									  'salary' => $request->element('salary_desired'),
									  'properties' => serialize($properties),
									  'date_created' => date("Y-m-d H:i:s"),
									  'status' => 1);
						$newId = $userProfiles->updateData($data,$profileInfo->getId());
						$template->assign("infoClass","infoBk");
						unset($_SESSION['rand_code']);
						header('location:/index.php?op=estore&act=create-profile&plus=ntv&rcode=4');
					}else{
						
						#Property 
						$properties = array('');
						$properties = array('foreign_language' => $request->element('foreign_language'),
											'level_computer' => $request->element('level_computer'),
											'degree_certificates' => $request->element('degree_certificates'),
											'work_process' => $request->element('work_process'),
											'outstanding_skills' => $request->element('outstanding_skills'),
											'career_goals' => $request->element('career_goals')
											);
						# End property
						$data = array('store_id' => $storeId,
									  'user_id'  => $customerInfo->getId(),
									  'type'	=> 1,
									  'education_level' => $request->element('education_level'),
									  'job_category' => $request->element('job_category'),
									  'years_experience' => $request->element('years_experience'),
									  'select_category' => $request->element('select_category'),
									  'place_work' => $request->element('place_work'),
									  'mode_work' => $request->element('mode_work'),
									  'salary' => $request->element('salary_desired'),
									  'properties' => serialize($properties),
									  'date_created' => date("Y-m-d H:i:s"),
									  'status' => 1);
						$newId = $userProfiles->addData($data);
						$template->assign("infoClass","infoBk");
						unset($_SESSION['rand_code']);
						header('location:/index.php?op=estore&act=create-profile&plus=ntv&rcode=4');
					}
			}
		break;

	
}

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['register'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);
}



}



# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['education_level'] = $validate->validString($request->element('education_level'),$messages['education_level']);
	$error['INPUT']['select_category'] = $validate->validString($request->element('select_category'),$messages['select_category']);
	$error['INPUT']['place_work'] = $validate->validString($request->element('place_work'),$messages['place_work']);
	$error['INPUT']['mode_work'] = $validate->validString($request->element('mode_work'),$messages['mode_work']);
	$error['INPUT']['salary_desired'] = $validate->validString($request->element('salary_desired'),$messages['salary_desired']);
	$error['INPUT']['request_work'] = $validate->pasteString($request->element('request_work'));
	
	$code = strtolower($request->element('code'));
	$error['INPUT']['code'] = $validate->validCode($code,$messages['security']);
	if($error['INPUT']['education_level']['error'] || $error['INPUT']['select_category']['error'] || $error['INPUT']['place_work']['error'] || $error['INPUT']['mode_work']['error'] || $error['INPUT']['salary_desired']['error'] || $error['INPUT']['code']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateDataUser($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['education_level'] = $validate->validString($request->element('education_level'),$messages['education_level']);
	$error['INPUT']['job_category'] = $validate->validString($request->element('job_category'),$messages['job_category']);
	$error['INPUT']['years_experience'] = $validate->validString($request->element('years_experience'),$messages['years_experience']);
	$error['INPUT']['select_category'] = $validate->validString($request->element('select_category'),$messages['select_category']);
	$error['INPUT']['place_work'] = $validate->validString($request->element('place_work'),$messages['place_work']);
	$error['INPUT']['mode_work'] = $validate->validString($request->element('mode_work'),$messages['mode_work']);
	$error['INPUT']['salary_desired'] = $validate->validString($request->element('salary_desired'),$messages['salary_desired']);
	$error['INPUT']['foreign_language'] = $validate->pasteString($request->element('foreign_language'));
	$error['INPUT']['level_computer'] = $validate->pasteString($request->element('level_computer'));
	$error['INPUT']['degree_certificates'] = $validate->pasteString($request->element('degree_certificates'));
	$error['INPUT']['work_process'] = $validate->pasteString($request->element('work_process'));
	$error['INPUT']['outstanding_skills'] = $validate->pasteString($request->element('outstanding_skills'));
	$error['INPUT']['career_goals'] = $validate->pasteString($request->element('career_goals'));
	$code = strtolower($request->element('code'));
	$error['INPUT']['code'] = $validate->validCode($code,$messages['security']);
	if($error['INPUT']['education_level']['error'] || $error['INPUT']['job_category']['error'] || $error['INPUT']['years_experience']['error'] || $error['INPUT']['select_category']['error'] || $error['INPUT']['place_work']['error'] || $error['INPUT']['mode_work']['error'] || $error['INPUT']['salary_desired']['error'] || $error['INPUT']['code']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>