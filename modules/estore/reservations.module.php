<?php
/*************************************************************************
Estore reservation module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                  
**************************************************************************/
include_once(ROOT_PATH."classes/dao/provinces.class.php");
include_once(ROOT_PATH.'classes/dao/emails.class.php');
include_once(ROOT_PATH."classes/dao/orders.class.php");
include_once(ROOT_PATH."classes/dao/orderitems.class.php");
$templateFile = "reservations.tpl.html";
$provinces = new Provinces();
$emails = new Emails($sId);
$orders= new Orders($sId);
$plus = $request->element('plus');

# Get Register
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$messages["result_code"][$result_code]);
# At the beginning
$provincesList = $provinces->createComboBox();
if($provincesList) $template->assign('provincesList',$provincesList);
if(isset($_SESSION['store_customerId'])) $uId = (int)$_SESSION['store_customerId'];
if(isset($uId)) $customerInfo = $customers->getObject($uId,'id');
if($customerInfo){
	$provincesList = $provinces->createComboBox($customerInfo->getProperty('province'), 'id', 'name');
	$template->assign('provincesList',$provincesList);	
	$template->assign('item',$customerInfo);
}
$priceRoom = $estore->getProperty('price_room');
$template->assign("priceRoom",$priceRoom);		
$infoClass = "infoText";
$template->assign("infoClass",$infoClass);

$quantity=1;
if ($plus == 'reservation'){ 
	$quantity = $request->element('quantity');
}
$template->assign('quantity',$quantity);
if($plus=="order"){
if($_POST){	
$startDate= strtotime($request->element('date_arrive'));
$endDate= strtotime($request->element('date_leave'));
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		$template->assign("infoClass","error");
		$template->assign('note',$messages["reservation_error"]);
		$listProvince = $provinces->createComboBox($request->element('province'), 'id', 'name');
		$template->assign('listProvince',$listProvince);
	} else { # Valid data input
		# check duplicate customer username
			if($endDate-$startDate < 0) {
				$validate['INPUT']['date_leave']['message'] = $messages['date_leave_invalid'];
				$validate['INPUT']['date_leave']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);	
				$template->assign("infoClass","error");
				$template->assign('note',$messages["reservation_error"]);
			}
	}
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			$order_status = $estore->getProperty('order_status');
			if(isset($_SESSION['store_customerId'])) $userId = $_SESSION['store_customerId'];
					else $userId = '';
					$numDays=($endDate-$startDate)/86400;
					$total=number_format($numDays*$request->element('quantity')*$priceRoom);
					# OK, create the new order
					$properties=array('date_arrive'=> $request->element('date_arrive'),
									  'date_leave'=> $request->element('date_leave'),
									  'quantity'=> $request->element('quantity'),
									  'total'=> $total);
					$fields = array('store_id'		=> $sId,
									'user_id'		=> $userId,
									'name'			=> $request->element('name'),
									'email'			=> $request->element('email'),
									'address'		=> $request->element('address'),
									'province'		=> $request->element('province'),
									'tel'			=> $request->element('tel'),
									'cell'			=> $request->element('cell'),
									'rname'			=> '',
									'raddress'		=> '',
									'rprovince'		=> '',
									'rtel'			=> '',
									'rcell'			=> '',
									'rdate'			=> '',
									'rnote'			=> $request->element('orther_request'),
									'created'		=> date("Y-m-d H:i:s"),
									'updated'		=> '',
									'properties'	=>  serialize($properties),
									'status'		=> $order_status
									);
									
					$orderId = $orders->addData($fields);
					$order = $orders->getObject($orderId);
					$template->assign('order',$order);
			if(isset($_SESSION['lang']))$lang=$_SESSION['lang'];
			else $lang='vn';
			if($lang=='vn')		
			$emailItem = $emails->getObject('RESERVATION_EMAIL','name',"status = 1");
			else
			$emailItem = $emails->getObject('RESERVATION_EMAIL_EN','name',"status = 1");
			if($emailItem){
				$tokens = explode(',',$emailItem->getTokens());
				$bodyEmail = $emailItem->getContent();
				$subjectEmail = $emailItem->getTitle();
				foreach($tokens as $token){
					$subjectEmail = str_replace("{".$token."}",'%s',$subjectEmail);
					$bodyEmail = str_replace("{".$token."}",'%s',$bodyEmail); 
				}
			}else{
				$subjectEmail = $messages["reservation_email_title"];
				$bodyEmail = $messages["reservation_email_body"];
			}
			$siteName=$estore->getName();
			$To = $request->element('email');
			$Subject     =sprintf($subjectEmail,$siteName);
			$from=$estore->getEmail();
			$link="<a href=".DOMAIN."/$lang/reservation/".$request->element('cell').'-'.$orderId.".html>".DOMAIN."/$lang/reservation/".$request->element('cell').'-'.$orderId.'.html</a>';

			$Body = sprintf($bodyEmail,$request->element('name'),$request->element('name'),$request->element('address'),$request->element('tel'),$request->element('cell'),$request->element('date_arrive'),$request->element('date_leave'),$total,$link,$siteName);
			$header = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\nReply-to: $from";
			$ok = mail($To,$Subject,$Body,$header);
			unset($_SESSION['rand_code']);
			header("location:/$lang/reservation-successfull.htm");
		}
	}
}
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['reservation'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$messages['individual_or_organization']);
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'));
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$messages['address']);
	$error['INPUT']['other_request'] = $validate->pasteString($request->element('other_request'));
	$error['INPUT']['date_arrive'] = $validate->validString($request->element('date_arrive'),$messages['date_arrive']);
	$error['INPUT']['date_leave'] = $validate->validString($request->element('date_leave'),$messages['date_leave']);
	$error['INPUT']['tel'] = $validate->validNumber($request->element('tel','0'),$messages['tel']);
	$error['INPUT']['adults'] = $validate->validNumber($request->element('adults'),$messages['adults']);
	$error['INPUT']['children'] = $validate->validNumber($request->element('children',0),$messages['children']);
	$error['INPUT']['cell'] = $validate->validNumber($request->element('cell'),$messages['cell']);
	$error['INPUT']['quantity'] = $validate->validNumber($request->element('quantity'),$messages['quantity']);
	$code = strtolower($request->element('code'));
	$error['INPUT']['code'] = $validate->validCode($code,$messages['security']);
	if($error['INPUT']['name']['error'] ||$error['INPUT']['quantity']['error'] ||$error['INPUT']['address']['error'] || $error['INPUT']['email']['error'] || $error['INPUT']['cell']['error'] || $error['INPUT']['code']['error']|| $error['INPUT']['date_arrive']['error']|| $error['INPUT']['date_leave']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
# Page title, keywords, description
$pageTitle = $messages['reservation'];
?>