<?php
/*************************************************************************
Estore register module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                  
**************************************************************************/
include_once(ROOT_PATH."classes/dao/provinces.class.php");
include_once(ROOT_PATH.'classes/dao/emails.class.php');
$templateFile = "list-employment.tpl.html";
$provinces = new Provinces();
$emails = new Emails($sId);
include_once(ROOT_PATH."classes/dao/userprofiles.class.php");
$userProfiles = new UserProfiles($sId);
$plus = $request->element('plus');
$condition = "`status` = 1";
if($customerInfo)	$condition .= " and `user_id` = '".$customerInfo->getId()."'";
if($plus == 'ntd')	$condition .= " and `type` = 2";
elseif($plus == 'ntv')	$condition .= " and `type` = 1";
$profileInfos = $userProfiles->getObjects(1,$condition);
if($profileInfos)	$template->assign('listItems',$profileInfos);
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['register'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
if($plus == 'ntv')	$pageTitle = $messages['list_employment'];
if($plus == 'ntd')	$pageTitle = $messages['list_recruitment'];
?>