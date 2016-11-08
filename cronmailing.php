<?php
/*************************************************************************
Coder: PhanTom
5 minutes running cron 1 times
**************************************************************************/
$time_start = microtime(true);
error_reporting(9);
if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', dirname(__FILE__).'/');
}
$log_campaign="campaign.txt";
if(!file_exists($log_campaign)) mkdir($log_campaign);
$log = fopen("$log_campaign", "a+");
include_once(ROOT_PATH.'includes/config.inc.php');
include_once(ROOT_PATH.'includes/constant.inc.php');
include_once(ROOT_PATH.'classes/data/translator.class.php');
include_once(ROOT_PATH.'includes/functions.inc.php');
include_once(ROOT_PATH.'classes/database/mysql.class.php');
include_once(ROOT_PATH.'classes/security/boot.class.php');
include_once(ROOT_PATH.'classes/template/smarty.class.php');
include_once(ROOT_PATH.'classes/dao/estores.class.php');
require_once(ROOT_PATH.'classes/PHPMailer/class.phpmailer.php');

# Setting time zone
if(function_exists('date_default_timezone_set')) date_default_timezone_set(TIME_ZONE);

# Database connection
$db = new DB();
echo 'hello';
$time = date("Y-m-d");
$sql = "SELECT * FROM ".DB_PREFIX."campaign WHERE `status`=1 AND (`appointment`='$time' OR `appointment` IS NULL) LIMIT 0,1";
$result = $db->query($sql);
$row = $db->fetchArray($result);
if($row['id']){
	$send=$row['send']?$row['send']:0;
	if($send==0) fwrite($log,date("Y-m-d H:i:s").'   | '.$row['id']."   | Campaign began running.\n");
	$failed=$row['failed']?$row['failed']:0;
	$from =$row['email'];
	$namedisplay=$row['name'];
	$id_mail=$row['id_email']?$row['id_email']:0;
	
	# Get Email Content
	$sqlemail = "SELECT * FROM ".DB_PREFIX."newstemplate WHERE `id` = '".$id_mail."' AND `status`=1 LIMIT 0,1";
	$mailTemplate = $db->query($sqlemail);
     
	   $rows = $db->fetchArray($mailTemplate);
	   if($rows['id']){# If isset mail tempalte
	   $subjectEmail = stripslashes($rows['title']);
	   $bodyEmail = stripslashes($rows['content']);
	   $headerEmail = "Content-type: text/html; charset=utf-8\r\nFrom: $namedisplay\r\nReply-to: $from";
	   #---List Group Customer
	   $sqls = "SELECT DISTINCT(`".DB_PREFIX."newsletter_customer`.`id`) FROM `".DB_PREFIX."newsletter_customer`,`".DB_PREFIX."campaign_cate`,`".DB_PREFIX."emailcustomer_cate` WHERE `".DB_PREFIX."campaign_cate`.`campaign` = '".$row['id']."' AND `".DB_PREFIX."campaign_cate`.`group` = `".DB_PREFIX."emailcustomer_cate`.`group` AND `".DB_PREFIX."emailcustomer_cate`.`customer` = `".DB_PREFIX."newsletter_customer`.`id` AND `".DB_PREFIX."newsletter_customer`.`campaign` != '".$row['id']."' LIMIT 0,50";
	   $resultId = $db->query($sqls);
	   $objects = array();
	   while($cus = $db->fetchArray($resultId)){$objects[] = $cus['id'];} 
	   
	   if($objects){
		$allId=implode(",",$objects);
		$sqlss = "SELECT * FROM ".DB_PREFIX."newsletter_customer WHERE `id` in('".$allId."') LIMIT 0,50";  
		$results = $db->query($sqlss);
		  while($em = $db->fetchArray($results)) {
			$etitle = $subjectEmail;
			$etitle = str_replace('{NAME}',$em['name'],$etitle);
			$ebody = $bodyEmail;
			$ebody = str_replace('{NAME}',stripslashes($em['name']),$ebody);
			$ebody = str_replace('{EMAIL}',stripslashes($em['email']),$ebody);
			$ebody = str_replace('{COMPANY}',stripslashes($em['company']),$ebody);
		  
		
			$mail = new PHPMailer();
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(true);
			$mail->AddReplyTo($from);
			$mail->Subject = $etitle;
			$mail->MsgHTML($ebody);						
			$mail->AddAddress($em['email']);	
			$mail->SetFrom($from,$namedisplay);
			$arrayF = array();
			$arrayS = array();
			if(!$mail->Send()) {
			   $failed++;
			   $arrayF[]=$em['id'];
			   $sqlupdate = "UPDATE ".DB_PREFIX."newsletter_customer set `status`=1,`failed`= '".($em['send']+1)."',`campaign`='".$row['id']."' WHERE id = '".$em['id']."'";	
			   $db->query($sqlupdate);
			  echo "Mailer Error: " . $mail->ErrorInfo;
			 }else{
				$send++;
				 $arrayS[]=$em['id'];
				$sqlupdate = "UPDATE ".DB_PREFIX."newsletter_customer set `status`=1,`send`= '".($em['send']+1)."',`campaign`='".$row['id']."' WHERE id = '".$em['id']."'";	
						
				$db->query($sqlupdate);
				
			}
		
	      }
		  $sqlupdates = "UPDATE ".DB_PREFIX."campaign set `send`= '".$send."',`failed`='".$failed."' WHERE id = '".$row['id']."'";			
		 $db->query($sqlupdates);
		 fwrite($log,date("Y-m-d H:i:s").'   | '.$row['id'].'   | Running campaigns.               | '.count($arrayS).'__:'.implode(",",$arrayS).'   | '.count($arrayF).'__:'.implode(",",$arrayF)."\n");
	 }else{
		$sqlupdates = "UPDATE ".DB_PREFIX."campaign set `status`=3 WHERE id = '".$row['id']."'";			
	    $db->query($sqlupdates);
		$sqlupdatess = "UPDATE ".DB_PREFIX."newsletter_customer set `campaign`=0 WHERE `status` = 1";			
	    $db->query($sqlupdatess);
		fwrite($log,date("Y-m-d H:i:s").'   | '.$row['id'].'   | Send mail campaign completed.'."\n");
		 
	 }
  }else{
	  #---Can not be triggered email campaign
	 fwrite($log,date("Y-m-d H:i:s")."   | ".$row['id']."   |  No activation email"."\n");
	 fwrite($log,date("Y-m-d H:i:s").'   | '.$row['id'].'   | Send mail campaign completed.'."\n");
	 $sqlupdates = "UPDATE ".DB_PREFIX."campaign set `status`=3 WHERE id = '".$row['id']."'";			
	 $db->query($sqlupdates);
  }
}else{ #---no campaign
	fwrite($log,date("Y-m-d H:i:s")."   |     |  No campaign"."\n");
}

echo date("Y-m-d H:i:s")." Sending email for campaign <strong>".$row['id']."</strong>...";

# Close database connection
$db->close();
fclose($log);

?>