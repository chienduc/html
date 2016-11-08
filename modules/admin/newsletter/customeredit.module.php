<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'newslettercustomer.tpl.html';
include_once(ROOT_PATH.'classes/dao/emailcustomer.class.php');
include_once(ROOT_PATH.'classes/dao/groupcustomer.class.php');
include_once(ROOT_PATH.'classes/dao/areas.class.php');
include_once(ROOT_PATH.'classes/dao/emailcustomercate.class.php');
$customer = new EmailCustomer($storeId);
$areas = new Areas($storeId);
$customerCategories = new GroupCustomer($storeId);
$emailCustomerCate = new EmailCustomerCate();

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=newsletter',
				$amessages['manage_customer'] => '/'.ADMIN_SCRIPT.'?op=newsletter&act=customer',
				$amessages['edit_customer'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=newsletter&act=customer';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_customer'] => '#',
				$amessages['list_customer_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_customer_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$customerInfo = $customer->getObject($id);

if(!$customerInfo) {
	$template->assign('validItem',0);
} else{
	$template->assign('validItem',1);

	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$customerInfo = $customer->getObject($id);
			$template->assign('itemInfo',$customerInfo);
			$allId=$emailCustomerCate->getGroupFromCampaign($id);
			$listGroup = $customerCategories->generateListbox($allId);
            if($listGroup) $template->assign('listGroup',$listGroup);
			     # Category combo box
	     	$Combo = $areas->generateCombo($customerInfo->getProvince());
		     if($Combo) $template->assign('Combo',$Combo);
		if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		} else { # Valid data input
		     # Category combo box
		    $Combo = $areas->generateCombo($request->element('province'));
		    if($Combo) $template->assign('Combo',$Combo);
			# Category combo box
			$listGroup = $customerCategories->generateListbox($request->element('group_customer'));
            if($listGroup) $template->assign('listGroup',$listGroup);
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
					$data = array('store_id' => $storeId,
						  'email' => $request->element('email'),
						  'name' => Filter($request->element('name')),
						  'sex' => Filter($request->element('sex')),
						  'company' => Filter($request->element('company')),
						  'website' => $request->element('website'),
						  'province' => $request->element('province'),
						  'details' => $request->element('detail'),
						  'status' => $request->element('status'),
						  'date_update' => date("Y-m-d H:i:s"));
					$customer->updateData($data,$id);
					
		    $emailCustomerCate->deleteData($id,'campaign');
			 #Add style restaurant
			 foreach($request->element("group_customer") as $group){
				      $data = array('customer' => $id,
						            'group' => $group);
				    $emailCustomerCate->addData($data);
		  	}
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_customer'],$request->element('title')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=newsletter&act=customer&mod=edit&lang=$lang&id=$id&rcode=7");
			
			}
		}
	} else { # Load customer category information to edit
		 $template->assign('item',$customerInfo);
         $allId=$emailCustomerCate->getGroupFromCampaign($id);
         $listGroup = $customerCategories->generateListbox($allId);
         if($listGroup) $template->assign('listGroup',$listGroup);
		 # Category combo box
		$Combo = $areas->generateCombo($customerInfo->getProvince());
		if($Combo) $template->assign('Combo',$Combo);
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
	$error['INPUT']['sex'] = $validate->validString($request->element('sex'),$amessages['sex']);
	$error['INPUT']['company'] = $validate->validString($request->element('company'),$amessages['company']);
	$error['INPUT']['website'] = $validate->validString($request->element('website'),$amessages['website']);
	$error['INPUT']['detail'] = $validate->validString($request->element('detail'),$amessages['detail']);
	$error['INPUT']['province'] = $validate->validString($request->element('province'),$amessages['province']);
    if($error['INPUT']['name']['error'] || $error['INPUT']['email']['error'] ){
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>