<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
#Static Page
include_once(ROOT_PATH.'classes/dao/static.class.php');
$templateFile = "static.tpl.html";
$statics = new StaticPage($sId);



#Get Static
$content = $request->element('slug'); 
$template->assign('content',$content);
if ($content){
	$aboutusItem = $statics->getObjectFromSlug($content); 
	if($aboutusItem) $template->assign('aboutusItem',$aboutusItem);
}

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');
if ($aboutusItem)	$navigationItems[] = array('name' => $aboutusItem->getTitle($lang), '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
if ($aboutusItem){  
	$pageTitle = $aboutusItem->getTitle($lang);
	$pageKeywords = $aboutusItem->getKeywords($lang);
	$pageDescription = $aboutusItem->getSapo($lang);
}
$template->assign('subFooter',1);
?>