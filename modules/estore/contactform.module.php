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
include_once(ROOT_PATH.'classes/dao/static.class.php');
$statics = new StaticPage($sId);
$contentItem = $statics->getObjectFromSlug('lien-he'); 
if($contentItem) $template->assign('contentItem',$contentItem);
if($_POST) {
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		$template->assign("infoClass","error");
		$template->assign('note',$messages["contact_error"]);	
	} else { # Valid data input
		include_once(ROOT_PATH.'classes/dao/emails.class.php');
		$emails = new Emails($sId);
		$emailItem = $emails->getObject('CONTACT_EMAIL','name',"status = 1");
			if($emailItem){
				$tokens = explode(',',$emailItem->getTokens());
				$bodyEmail = $emailItem->getContent();
				$subjectEmail = $emailItem->getTitle();
				foreach($tokens as $token){
					$subjectEmail = str_replace("{".$token."}",'%s',$subjectEmail);
					$bodyEmail = str_replace("{".$token."}",'%s',$bodyEmail); 
				}
			}else{
				$subjectEmail = $messages["register_email_title"];
				$bodyEmail = $messages["register_email_body"];
			}
		$To          = $estore->getEmail();
		$Subject     =sprintf($subjectEmail,DOMAIN);
		$from=strip_tags($_REQUEST['email']);
		$Body = sprintf($bodyEmail,DOMAIN,$request->element('name'),$request->element('address'),$request->element('email'),$request->element('tel'),$request->element('comment'),DOMAIN);
		$header = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\nReply-to: $from";
		$ok = mail($To,$Subject,$Body,$header);
		$template->assign("note",$messages['contact_success']);
		$template->assign("infoClass","info");
		unset($_SESSION['rand_code']);
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
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$messages['address']);
	$error['INPUT']['comment'] = $validate->validString($request->element('comment'),$messages['comment']);
	$error['INPUT']['tel'] = $validate->validString($request->element('tel'),$messages['tel']);
	$error['INPUT']['code'] = $validate->validString($request->element('code'),$messages['security']);
	$code = strtolower($request->element('code'));
    
    $error['INPUT']['fullname_error'] = $validate->pasteString($request->element('name'),'');
    $error['INPUT']['address_error'] = $validate->pasteString($request->element('address'),'');
    $error['INPUT']['tel_error'] = $validate->pasteString($request->element('tel'),'');
    $error['INPUT']['email_error'] = $validate->pasteString($request->element('email'),'');
    $error['INPUT']['wedding_error'] = $validate->pasteString($request->element('wedding'),'');
    $error['INPUT']['comment_error'] = $validate->pasteString($request->element('comment'),'');
    
	if(!isset($_SESSION['rand_code']) || $code != strtolower($_SESSION['rand_code'])) $error['INPUT']['code'] = $messages["invalid_security_code"].'<br />';
	if($error['INPUT']['email']['error'] || $error['INPUT']['fullname']['error'] || $error['INPUT']['comment']['error']||$error['INPUT']['code']['error']) {
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