<?php
/*************************************************************************
Estore category module
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd
Email: info@derasoft.com
Last updated: 06/19/2010
**************************************************************************/
include_once(ROOT_PATH."classes/dao/provinces.class.php");
include_once(ROOT_PATH."classes/dao/orders.class.php");
include_once(ROOT_PATH."classes/dao/orderitems.class.php");

$provinces = new Provinces();
$orders = new Orders($sId);
if($orderOn){
$plus = $request->element('plus','');
$orderCode = $request->element('orderCode');
$cell = $request->element('cell');

#Allow order config
$template->assign('orderRequireLogin',$estore->getProperty('order_require_login'));
$order_status = $estore->getProperty('order_status');
$template->assign('order_vat',$estore->getProperty('order_vat')/100); // Thue VAT
switch ($plus) {
	case 'checkorder':
		
		$order = $orders->getObject($orderCode,'id',"cell = '$cell'");
		if ($order){
			$templateFile = "vieworder.tpl.html";
			$oId = $order->getId();
			$orderItems = new OrderItems($oId);
			$cartItems = $orderItems->getObjects(1,"`order_id` = '$oId'",array('id'=>'ASC'),'');
			$template->assign('order',$order);
			$template->assign('cartItems',$cartItems);
		}else {
			$infoClass = "error";
			$templateFile = "checkorder.tpl.html";
			$error = $messages['order_error'];
			$template->assign('error',$error);
			$template->assign('infoClass',$infoClass);
		}
	break;
	case 'listorder':
		$templateFile = "listorder.tpl.html";
		if(isset($_SESSION['store_customerId'])){
			$Id = $_SESSION['store_customerId'];
			$estore->setProperty('orders_per_page', 20);
			$page = $request->element('pg');
			if(!$page) $page = 1;
			$rowsPages = $orders->getNumItems('id', "user_id = '$Id'",$estore->getProperty('orders_per_page'));
			$template->assign('rowsPages',$rowsPages);
			/// start page 
			$end = $page * $estore->getProperty('orders_per_page');
			if($end > $estore->getProperty('orders_per_page')) $start  = $end -  $estore->getProperty('orders_per_page') +1;
			else $start = 1;
			if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
			$template->assign('end',$end);
			$template->assign('start',$start);
			/// end page
			if($page < 1) $page = 1;
			if($page > $rowsPages['pages']) $page = $rowsPages['pages'];

			$listorder = $orders->getObjects($page,"user_id = '$Id'",array('created'=>'DESC'),$estore->getProperty('orders_per_page'));
			$template->assign("items",$listorder);
			
			# Generate pager
			if(isset($rowsPages)){
			$url = "/listorder/page-%d.html";
			$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
			$template->assign('pager',$pager);
			}
		}
		
	break;
		
	default:
		# At the beginning
		if(isset($_SESSION['store_userId'])!= NULL){
			$provincesList = $provinces->createComboBox($userInfo->getAreaId());
			if($provincesList)
				$template->assign('provincesList',$provincesList);

			$rprovincesList = $provinces->createComboBox();
			if ($rprovincesList)
				$template->assign('rProvincesList',$rprovincesList);
		}else{
			$provincesList = $provinces->createComboBox();
			$template->assign('provincesList',$provincesList);
			$template->assign('rProvincesList',$provincesList);
		}
		$templateFile = 'checkorder.tpl.html';
		
		break;
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'),$messages['email']);
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$messages['name']);
	$error['INPUT']['province'] = $validate->validString($request->element('province'),$messages['province']);
	$error['INPUT']['cell'] = $validate->validString($request->element('cell'),$messages['cell']);
	$error['INPUT']['rname'] = $validate->validString($request->element('name'),$messages['rname']);
	$error['INPUT']['raddress'] = $validate->validString($request->element('raddress'),$messages['raddress']);
	$error['INPUT']['rprovince'] = $validate->validString($request->element('rprovince'),$messages['rprovince']);
	$error['INPUT']['code'] = $validate->validString($request->element('code'),$messages['security']);
	$code = strtolower($request->element('code'));
	if(!isset($_SESSION['rand_code']) || $code != strtolower($_SESSION['rand_code'])) $error['INPUT']['code'] = $messages["invalid_security_code"].'<br />';
	if($error['INPUT']['email']['error'] || $error['INPUT']['comment']['error'] || $error['INPUT']['code']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}


function validateData1($request) {
	global $messages;
	$error = array();
	$validate = new Validate();
	if(strtolower($request->element('code'))!= strtolower($_SESSION['rand_code'])) $error[0] = $messages['invalid_security_code'];
	else $error[0] = 0;
	return $error;
}
}else	$templateFile = 'note.tpl.html';
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['order'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
$pageTitle = $messages['order'];
?>