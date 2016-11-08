<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'newsletteruploadfile.tpl.html';
include_once(ROOT_PATH.'classes/dao/emailcustomer.class.php');
include_once(ROOT_PATH.'classes/dao/areas.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$customer = new EmailCustomer($storeId);
$areas = new Areas($storeId);
$gallery_root = ROOT_PATH."upload/email/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=newsletter',
			
				$amessages['uploadfile'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=newsletter&act=uploadfile';
$listTabs = array($amessages['uploadfile'] => $tabLink.'&mod=add');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',1);

if($_POST && $request->element('doo') == 'submit') { 
   # Check if gallery folder is exists
   if(!file_exists($gallery_root)) mkdir("$gallery_root");
     # Files upload
     $files = isset($_FILES['files'])?$_FILES['files']:'';
     if($files['name']){
        $img = addslashes(Filter(rand()."_".$files['name']));
		$tmp_img = $files['tmp_name'];
		$size = $files['size'];
		$type=strtolower(substr($img,-3));
		if(preg_match("/".ALLOW_FILE_UPLOAD_EMAIL."/",strtolower($type))) {
			move_uploaded_file($tmp_img,$gallery_root.$img);
			$ufiles = $img;
			
		// Get Excel
		$dom = new DOMDocument();
		$dom->load($gallery_root.$ufiles);
        $row = $dom->getElementsByTagName("Row");
        $first_row = TRUE;
		$j=0;
         foreach($row as $r){
			$j++;
            if($first_row){
				$pro='';$name='';$sex='';$company='';$website='';$details='';
			    $em = $r->getElementsByTagName("Cell")->item(0)->nodeValue;
			    if(isset($r->getElementsByTagName("Cell")->item(5)->nodeValue)) 	                   $pro = $r->getElementsByTagName("Cell")->item(5)->nodeValue;
			    if(isset($r->getElementsByTagName("Cell")->item(1)->nodeValue)) 	                   $name = $r->getElementsByTagName("Cell")->item(1)->nodeValue;
				if(isset($r->getElementsByTagName("Cell")->item(3)->nodeValue)) 	                   $company = $r->getElementsByTagName("Cell")->item(3)->nodeValue;
				if(isset($r->getElementsByTagName("Cell")->item(2)->nodeValue)) 	                   $sex = $r->getElementsByTagName("Cell")->item(2)->nodeValue;
				if(isset($r->getElementsByTagName("Cell")->item(4)->nodeValue)) 	                   $website = $r->getElementsByTagName("Cell")->item(4)->nodeValue;
				if(isset($r->getElementsByTagName("Cell")->item(6)->nodeValue)) 	                   $details = $r->getElementsByTagName("Cell")->item(6)->nodeValue;
			    $em=trim($em);
			    $pro=trim($pro);
				if(filter_var($em,FILTER_VALIDATE_EMAIL)){
		        if(!$customer->checkDuplicate($em)){
				$prId=$areas->getIdFromName($pro);
				// Add
				$data = array('store_id'=> $storeId,
							  'email'   => $em,
							  'name'    => $name,
							  'sex'     => $sex,
							  'company' => $company,
							  'website' => $website,
							  'province'=> $prId,
							  'details' => $details,
							  'status'  => 3,
							  'date_created' => date("Y-m-d H:i:s"));
				$customer->addData($data);	
				 }# if Not isset email
				 
				}# Email Ivaide
		
	            }
            }
			unlink($gallery_root.$img);
			$template->assign('result_code',"6");
            $first_row = FALSE;	
		}else $template->assign('error',"File không hợp lệ.");
   }else $template->assign('error',"Không tồn tại File");
}

?>