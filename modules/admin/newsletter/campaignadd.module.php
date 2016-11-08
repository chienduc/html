<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'newslettercampaign.tpl.html';
include_once(ROOT_PATH.'classes/dao/campaign.class.php');
include_once(ROOT_PATH.'classes/dao/emailtemplate.class.php');
include_once(ROOT_PATH.'classes/dao/groupcustomer.class.php');
include_once(ROOT_PATH.'classes/dao/campaigncate.class.php');

$groupCustomer = new GroupCustomer($storeId);
$campaign = new Campaign($storeId);
$campaignCate = new CampaignCate();
$emailTemplate = new EmailTemplate($storeId);

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=newsletter',
				$amessages['manage_campaign'] => '/'.ADMIN_SCRIPT.'?op=newsletter&act=campaign',
				$amessages['add_new'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=newsletter&act=campaign';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => '',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

$comboEmail = $emailTemplate->generateCombo($request->element('id_email'));
if($comboEmail) $template->assign('comboEmail',$comboEmail);

$listGroup = $groupCustomer->generateListbox($request->element('group_customer'));
if($listGroup) $template->assign('listGroup',$listGroup);

if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		
	} else { # Valid data input
		if(!$validate['invalid']) {
			
			$data = array('store_id' => $storeId,
						  'name' => $request->element('name'),
						  'email' => $request->element('email'),
						  'id_email' => $request->element('id_email'),
						  'appointment' => Changedate($request->element('appointment')),
						  'date_created' => date("Y-m-d H:i:s"),
						  'status' => 0);
			$newId = $campaign->addData($data);
			
			#Add 
			foreach($request->element("group_customer") as $group){
				      $data = array('campaign' => $newId,
						            'group' => $group);
				$campaignCate->addData($data);
			}
					
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_campaign'],$request->element('username')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=newsletter&act=campaign&mod=list&rcode=6");
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
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'),$amessages['email']);
    if($error['INPUT']['name']['error'] || $error['INPUT']['email']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>