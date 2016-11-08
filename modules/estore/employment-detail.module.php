<?php
/*************************************************************************
Module new detail
----------------------------------------------------------------
DeraMCS 3.0 Project
Company: Derasoft Co., Ltd                                                                   
Coder: Tran Thi My Xuyen
Email: info@derasoft.com
Last updated: 06/06/2012
**************************************************************************/
$templateFile = "employment-detail.tpl.html";

include_once(ROOT_PATH.'classes/dao/userprofiles.class.php');
$userProfiles = new UserProfiles($sId);
# langgues
if(isset($_SESSION['lang']))$lang=$_SESSION['lang'];
else $lang='vn';
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');

#article
$aId = $request->element('id'); 
$cateslug = $request->element('cateslug');
$estore->setProperty('articles_per_other', 5);
if ($aId){
	$objectItem = $userProfiles->getObject($aId); 
	if($objectItem){
			$template->assign('objectItem',$objectItem);
			$userId = $objectItem->getUserId();
			## other article
			$articleotherItems = $userProfiles->getObjects(1,"status=1 AND id <> $aId",array('date_created'=>'DESC'),$estore->getProperty('articles_per_other'));
			$template->assign('otherItems',$articleotherItems);
			
			/*# Page title, keywords, description
			$pageTitle = $objectItem->getTitle($lang);
			$pageKeywords = $objectItem->getKeyword($lang);
			$pageDescription = $objectItem->getSapo($lang);*/
		}
}
//Navigation
$template->assign('navigationItems',$navigationItems);

?>