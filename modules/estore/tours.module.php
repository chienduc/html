<?php
/*************************************************************************
Module contactform
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
**************************************************************************/
$templateFile = 'booktour.tpl.html';
#Contact
include_once(ROOT_PATH.'classes/dao/requests.class.php');
include_once(ROOT_PATH.'classes/dao/products.class.php');
$requests = new Requests();
$products = new Products($sId);

$id=$request->element('id');
$productItem = $products->getObject($id);

if($productItem){
	$template->assign('productItem',$productItem);	
	
}


if($_POST) {
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		
	} else { # Valid data input
	
	
 $data = array('name' => Filter($request->element('fullname')),
			  'email' => $request->element('email'),
			  'answer' => $request->element('comment'),
			  'id_service' => $request->element('id'),
			  'tel' => $request->element('tel'),
			  'listhotel' => $listhotel,
			  'country' => $request->element('country'),
			  'number' => $request->element('number'),
		      'txtdate' => $request->element('txtDate'),
			  'properties' => serialize($properties),
			  'status' =>  1,
			  'created' => date("Y-m-d H:i:s"));
     $newId = $requests->addData($data);
	 header("location: /booktours/success.html");
		
	}
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'),$messages['email']);
	$error['INPUT']['fullname'] = $validate->validString($request->element('fullname'),$messages['fullname']);
	$error['INPUT']['comment'] = $validate->validString($request->element('comment'),$messages['comment']);
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'),$messages['tel']);
	$error['INPUT']['txtDate'] = $validate->validString($request->element('txtDate'),$messages['txtDate']);
	$error['INPUT']['number'] = $validate->validString($request->element('number'),$messages['number']);
	$error['INPUT']['country'] = $validate->validString($request->element('country'),$messages['country']);

	
	if($error['INPUT']['email']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['tel']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['contacts'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
$pageTitle = $messages['contacts'];
?>