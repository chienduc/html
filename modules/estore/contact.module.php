<?php
/*************************************************************************
Module contactform
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
**************************************************************************/
$templateFile = 'contact.tpl.html';
#Contact
include_once(ROOT_PATH.'classes/dao/requests.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$requests = new Requests();

if($_POST) {
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);		
	
	} else { # Valid data input
		
$data = array('name' => Filter($request->element('txtNameR')),
			  'email' => $request->element('txtEmailR'),
			  'answer' => $request->element('txtComment'),
			  'tel' => $request->element('txtDienThoaiR'),
			  'status' =>  1,
			  'created' => date("Y-m-d H:i:s"));
       $newId = $requests->addData($data);
	   header("location: ".$_SERVER['HTTP_REFERER']);
		
		
		
	}
}
# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['txtEmailR'] = $validate->validEmail($request->element('txtEmailR'),$messages['email']);
	$error['INPUT']['txtNameR'] = $validate->validString($request->element('txtNameR'),$messages['fullname']);
	$error['INPUT']['txtComment'] = $validate->validString($request->element('txtComment'),$messages['comment']);
	$error['INPUT']['txtDienThoaiR'] = $validate->validString($request->element('txtDienThoaiR'),$messages['tel']);

    
   
    
if($error['INPUT']['txtEmailR']['error'] || $error['INPUT']['txtNameR']['error'] || $error['INPUT']['txtComment']['error']) {
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