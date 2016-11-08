<?php
/***************************************************************
Module Contact
Coder: Phantom
****************************************************************/

# Get value
$tel = $request->element('tel');
$address = $request->element('address');
$company = $request->element('company');
$office = $request->element('office');
$email = $request->element('email');
$name = $request->element('name');
$detail = $request->element('detail');
$website = $request->element('website');
$fax = $request->element('fax');

# Valid data input
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
		$subjectEmail = $messages["contact_email_title"];
		$bodyEmail = $messages["contact_email_body"];
	}
$To          = $estore->getEmail();
$Subject     =sprintf($subjectEmail,DOMAIN);
$from=strip_tags($email);
$Body = sprintf($bodyEmail,DOMAIN,$name,$office,$company,$address,$email,$tel,$website,$fax,$detail,DOMAIN);
$header = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\nReply-to: $from";
$ok = mail($To,$Subject,$Body,$header);
print_r($ok);
# if return 1 True else False
echo '1';
?>