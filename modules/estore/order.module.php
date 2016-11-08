<?php
/*************************************************************************
Module Order
----------------------------------------------------------------
DerCMS 3.0 Project
Company: Derasoft Co., Ltd
Coder: Tran Thi My Xuyen
Email: info@derasoft.com
Last updated: 05/06/2012
**************************************************************************/
include_once(ROOT_PATH."classes/data/validate.class.php");
include_once(ROOT_PATH."classes/dao/provinces.class.php");
include_once(ROOT_PATH."classes/dao/areas.class.php");
include_once(ROOT_PATH."classes/dao/orders.class.php");
include_once(ROOT_PATH."classes/dao/orderitems.class.php");

$validate = new Validate();
$provinces = new Provinces();
$areas = new Areas($sId);
$orders = new Orders($sId);

#Allow order config
$template->assign('orderRequireLogin',$estore->getProperty('order_require_login'));
$order_status = $estore->getProperty('order_status');
$template->assign('order_vat',$estore->getProperty('order_vat'));
$template->assign('order_hours',$estore->getProperty('order_hours'));
$template->assign('datePicker',1);
$plus = $request->element('plus','');
if($orderOn){
switch ($plus) {
	case 'confirmOrder':
		# Confirm the information before creating the order
		$validate = validateData($request);
		if(!$validate['invalid']) {	# data input is not in valid form
			# Get province name & recipient's province name
			$province = $provinces->getNameFromId($request->element('province'));
			$rprovinceItem = $areas->getObject($request->element('rprovince'),'id',"status = 1");
			$template->assign('province',$province);
			if($rprovinceItem){
			$template->assign('rprovince',$rprovinceItem->getName());
			$template->assign('rprovinceItem',$rprovinceItem);
			}
			
			# Get items in cart
			$cartItems = $carts->getObjects(1, '1>0', $sort = array('time_stamp' => 'desc'), 100);
			if($cartItems) $template->assign("cartItems",$cartItems);
			$templateFile = 'orderstep2.tpl.html';
		} else {								# Form data is not valid, return to the order page
			
			$template->assign('error',$validate);	
			$template->assign("infoClass","error");
			$template->assign('note',$messages["order_error"]);
			$provincesList = $provinces->createComboBox($request->element('province'));
			$template->assign('provincesList',$provincesList);
			$rProvincesList = $areas->generateCombo("status = 1", $request->element('rprovince',2));
			$template->assign('rProvincesList',$rProvincesList);
			$templateFile = 'orderstep1.tpl.html';
		}
		$template->assign('order',$_REQUEST);
		$template->assign('plus',$plus);
		# Generate the navigation bar
		$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
		$navigationItems[] = array('name' => $messages['confirm_order'], 'url' => Url::genUrl('order',''), 'current' => '0');
		$template->assign('navigationItems',$navigationItems);
		break;
	case 'editOrder':
		# Customer needs to correct the information
		$provincesList = $provinces->createComboBox($request->element('province'));
		$rProvincesList = $areas->generateCombo("status = 1", $request->element('rprovince'));
		if($provincesList) $template->assign('provincesList',$provincesList);
		if($rProvincesList)	$template->assign('rProvincesList',$rProvincesList);

		$templateFile = 'orderstep1.tpl.html';
		$template->assign('order',$_REQUEST);
		$template->assign('plus',1);
		# Generate the navigation bar
		$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
		$navigationItems[] = array('name' => $messages['order'], 'url' => Url::genUrl('order',''), 'current' => '0');
		$template->assign('navigationItems',$navigationItems);
		break;
	case 'submitOrder':
		if ($_POST){
			# Confirm the information before creating the order
			$validate = validateData($request);
			if(!$validate['invalid']) {
				# Get items in cart
				$cartItems = $carts->getObjects(1, '1>0', $sort = array('time_stamp' => 'desc'), 100);
				if($cartItems) {
					if(isset($_SESSION['store_customerId'])) $userId = $_SESSION['store_customerId'];
					else $userId = '';
					# OK, create the new order
					$fields = array('store_id'		=> $sId,
									'user_id'		=> $userId,
									'name'			=> $request->element('name'),
									'email'			=> $request->element('email'),
									'address'		=> $request->element('address'),
									'province'		=> $request->element('province'),
									'tel'			=> $request->element('tel'),
									'cell'			=> $request->element('cell'),
									'rname'			=> $request->element('rname'),
									'raddress'		=> $request->element('raddress'),
									'rprovince'		=> $request->element('rprovince'),
									'rtel'			=> $request->element('rtel'),
									'rcell'			=> $request->element('rcell'),
									'rdate'			=> date("Y-m-d H:i:s",strtotime($request->element('rdate'))),
									'rnote'			=> $request->element('rnote'),
									'created'		=> date("Y-m-d H:i:s"),
									'updated'		=> '',
									'properties'	=> '',
									'status'		=> $order_status
									);
									
					$orderId = $orders->addData($fields);
					$order = $orders->getObject($orderId);
					$template->assign('order',$order);

					# Then create the order items
					$orderItems = new OrderItems($orderId);
					$products = new Products($sId);
					foreach ($cartItems as $cartItem) {
						$product = $products->getObject($cartItem->getProductId());
						$fields = array('order_id'		=> $orderId,
										'product_id'	=> $cartItem->getProductId(),
										'name'			=> $product->getName(),
										'quantity'		=> $cartItem->getQuantity(),
										'price'			=> $product->getPrice(),
										'properties'	=> ''				
										);
						$orderItems->addData($fields);
					}

					# Then empty the shopping cart
					$carts->emptyCart();
                    
					#Call to ORDERNEW addon
                    foreach($addons->getAddonFromEvent('ORDER_NEW') as $addon) {include_once(ROOT_PATH."addons/$addon/addon.php");}
					// Send email 
					include_once(ROOT_PATH.'classes/dao/emails.class.php');
					$emails = new Emails($sId);
					$emailItem = $emails->getObject('ORDER_CONFIRMATION','name',"status = 1");
						if($emailItem){
							$tokens = explode(',',$emailItem->getTokens()); 
							$bodyEmail = $emailItem->getContent();
							$subjectEmail = $emailItem->getTitle();
							foreach($tokens as $token){
								$subjectEmail = str_replace("{".$token."}",'%s',$subjectEmail);
								$bodyEmail = str_replace("{".$token."}",'%s',$bodyEmail); 
								
							}
						}else{
							$subjectEmail = $messages["order_email_title"];
							$bodyEmail = $messages["order_email_body"];
						}
			
					$adminEmail = $estore->getEmail();
					$cell = $request->element('cell');
					$url_2 = DOMAIN."/orderinfo/$cell/$orderId.html";
					$template->assign("url_2",$url_2);
					
					# Send mail
					/*if(SMTP_MAIL) {	# Use SMTP Mail
						include_once(ROOT_PATH."classes/mail/smtp.class.php");
						include_once(ROOT_PATH."classes/sasl/sasl.class.php");			
						$smtp = new SmtpMail;
						$smtp->SendMessage($adminEmail,array($_REQUEST['email']),array("From: $adminEmail","To: ".$_REQUEST['email'],"Cc: $adminEmail","Subject: ".$messages["order_email_title"],"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")),$body);
					} else { */# Use Sendmail
						$from = $adminEmail;
						$To = $adminEmail;
						$To1 = $request->element('email');
						$subject =  sprintf($subjectEmail,DOMAIN);
						$body = sprintf($bodyEmail,$request->element('name'),DOMAIN,$orderId,$request->element('cell'),$url_2,DOMAIN);
						$header = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\nReply-to: $from";
						$ok = mail($To,$subject,$body,$header);
						$ok2 = mail($To1,$subject,$body,$header);
					//}
					
					# Notice to the customer
					include_once(ROOT_PATH."classes/dao/paymentmethods.class.php");
					$paymentMethod = new PaymentMethods($sId);
					$template->assign('orderId',$orderId);
					$template->assign('url_2',$url_2);
					$listMethod = $paymentMethod->getObjects(1,"status = 1",array('position'=>'ASC'),'');
					$template->assign('listMethod',$listMethod);
					$template->assign('orderPassword',$request->element('cell'));
					$templateFile = 'orderstep4.tpl.html';
					#unset(session_id());
					unset($_SESSION['rand_code']);
				} else {
					# Generate the navigation bar
					$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
					$navigationItems[] = array('name' => $messages['order_error'], 'url' => Url::genUrl('order',''), 'current' => '0');
					$template->assign('navigationItems',$navigationItems);
					$templateFile = 'ordererror.tpl.html';
				}
			}else{
				$products = new Products($sId);
				$order = $orders->getObject($orderId);
				$template->assign('order',$order);
				$template->assign("products",$products);
				$template->assign('listerror',$listerror);
				$cartItems = $carts->getObjects(1, '1>0', $sort = array('time_stamp' => 'desc'), 100);
				if($cartItems) $template->assign("cartItems",$cartItems);
				$templateFile = 'orderstep2.tpl.html';
			}
		}
		break;
		case 'paymentOrder':
		if ($_POST){
			$orders = new Orders($sId);
			$oId = $request->element('oId');
			$orderCode = $request->element('orderCode');
			$url_2 = $request->element('url_2');
			$payment = $request->element('payment');
			$properties['payment'] = $payment;
			#$payment = $messages['payment'][$payment];
			$orders->updateData(array('properties'=>serialize($properties)), $oId);
			$templateFile = "orderstep4.tpl.html";
			$template->assign("oId",$oId);
			$template->assign("orderCode",$orderCode);
			$template->assign("url_2",$url_2);
			$request->element('rdate');
		}
		break;
		
	default:
		$templateFile = 'orderstep1.tpl.html';
		# At the beginning
		// list Province
		if(isset($_SESSION['store_customerId']) && $_SESSION['store_customerId']!= NULL)	$provincesList = $provinces->createComboBox( $customerInfo->getProperty('province'));
		else	$provincesList = $provinces->createComboBox();
		if($provincesList) $template->assign('provincesList',$provincesList);
		// List RProvince
		$rprovincesList = $areas->generateCombo("status = 1");
		if($rprovincesList)	$template->assign('rProvincesList',$rprovincesList);
		
		# Generate the navigation bar
		$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
		$navigationItems[] = array('name' => $messages['order'], 'url' => Url::genUrl('order',''), 'current' => '0');
		$template->assign('navigationItems',$navigationItems);
		break;
}
}else{	$templateFile = 'note.tpl.html';
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['order'], 'url' => Url::genUrl('order',''), 'current' => '0');
$template->assign('navigationItems',$navigationItems);
}
	
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'),$messages['email']);
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$messages['name']);
	$error['INPUT']['cell'] = $validate->validString($request->element('cell'),$messages['cell']);
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'),$messages['tel']);
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$messages['address']);
	$error['INPUT']['rname'] = $validate->validString($request->element('rname'),$messages['rname']);
	$error['INPUT']['raddress'] = $validate->validString($request->element('raddress'),$messages['raddress']);
	$error['INPUT']['rprovince'] = $validate->validString($request->element('rprovince'),$messages['rprovince']);
//	$error['INPUT']['rtel'] = $validate->validString($request->element('rtell'),$messages['tel']);
	$error['INPUT']['rcell'] = $validate->validString($request->element('rcell'),$messages['cell']);
	$error['INPUT']['rnote'] = $validate->validString($request->element('rnote'),$messages['comment']);
	$rdate = date("Y-m-d H:i:s",strtotime($request->element('rdate')));
	$error['INPUT']['rdate'] = $validate->validTime($rdate);
	if($error['INPUT']['email']['error'] || $error['INPUT']['name']['error'] || $error['INPUT']['cell']['error'] || $error['INPUT']['rname']['error'] || $error['INPUT']['raddress']['error'] || $error['INPUT']['rprovince']['error'] || $error['INPUT']['rcell']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>