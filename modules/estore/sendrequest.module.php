<?php
$customers = new Customers($sId);
if($_POST){	
	# Validate the data input
	$validate = validateData($request);
	if(!$validate['invalid']) {	# data input is not in valid form
		
		# check duplicate customer email
			if($customers->checkDuplicate($request->element('email'),'email')) {
				$validate['INPUT']['email']['message'] = $messages['email_aready'];
				$validate['INPUT']['email']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
		}
		if(!$validate['invalid']) {	
		# End property
		$data = array('store_id' => $storeId,
					  'username' => $request->element('email'),
					  'email' => Filter($request->element('email')),
					  'date_created' => date("Y-m-d H:i:s"),
					  'status' => 1);
		$newId = $customers->addData($data);
		header("location: ".$_SERVER['HTTP_REFERER']);
		}else header("location: ".$_SERVER['HTTP_REFERER']);
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $messages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['email'] = $validate->validEmail($request->element('email'));
	if($error['INPUT']['email']['error']) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>