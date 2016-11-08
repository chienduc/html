<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = "experiencedetail.tpl.html";
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Get new default
$navigationItems[] = array('name' =>  $messages['experiences'], 'url' => "/$lang/experiences.html", 'current' => '0');


include_once(ROOT_PATH.'classes/dao/experiencecategories.class.php');
$experienceCategories = new ExperienceCategories($sId);
include_once(ROOT_PATH.'classes/dao/experiences.class.php');
$experiences = new Experiences($sId);


$aId = $request->element('id'); 
$cateslug = $request->element('cateslug');
$slug = $request->element('slug');
$estore->setProperty('experiences_per_other', 5);
if ($aId){
	$objectItem = $experiences->getObject($aId); 
	if($objectItem){
		if((!$slug || in_array($slug,array('0','index',$objectItem->getSlug()))) && ( !$cateslug || in_array($cateslug,array('0','index',$objectItem->getCatSlug())))) {
			$template->assign('objectItem',$objectItem);
			$cateId = $objectItem->getCatId();
			
			$pCateId = $experienceCategories->getParentCategory($cateId); 
			## other article
			$articleotherItems = $experiences->getObjects(1,"status=1 AND cat_id= '$cateId' AND id <> $aId",array('date_created'=>'DESC'),$estore->getProperty('experiences_per_other'));
			$template->assign('otherItems',$articleotherItems);
			
			// Navigation
			$objectCate = $experienceCategories->getObject($objectItem->getCatId(),'id');
			if($objectCate) $navigationItems[] = array('name' => $objectCate->getName($lang), 'url' => $objectCate->getUrl($lang), 'current' => '0');
			$navigationItems[] = array('name' => $objectItem->getTitle($lang), '', 'current' => '1');
			# Page title, keywords, description
			$pageTitle = $objectItem->getTitle($lang);
			$pageKeywords = $objectItem->getKeyword($lang);
			$pageDescription = $objectItem->getSapo($lang);
		}else $templateFile = '404.tpl.html';
	}else $templateFile = '404.tpl.html';
}
# Navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>