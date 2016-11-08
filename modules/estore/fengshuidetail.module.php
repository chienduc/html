<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = "fengshuidetail.tpl.html";
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Get new default
$navigationItems[] = array('name' =>  $messages['fengshuis'], 'url' => "/$lang/fengshuis.html", 'current' => '0');

# Get article category
include_once(ROOT_PATH.'classes/dao/fengshuicategories.class.php');
include_once(ROOT_PATH.'classes/dao/fengshuis.class.php');
$fengshuiCategories = new FengshuiCategories($sId);
$fengshuis = new Fengshuis($sId);

$aId = $request->element('id'); 
$cateslug = $request->element('cateslug');
$slug = $request->element('slug');
$estore->setProperty('fengshuis_per_other', 5);
if ($aId){
	$objectItem = $fengshuis->getObject($aId); 
	if($objectItem){
		if((!$slug || in_array($slug,array('0','index',$objectItem->getSlug()))) && ( !$cateslug || in_array($cateslug,array('0','index',$objectItem->getCatSlug())))) {
			$template->assign('objectItem',$objectItem);
			$cateId = $objectItem->getCatId();
			
			$pCateId = $fengshuiCategories->getParentCategory($cateId); 
			## other article
			$articleotherItems = $fengshuis->getObjects(1,"status=1 AND cat_id= '$cateId' AND id <> $aId",array('date_created'=>'DESC'),$estore->getProperty('fengshuis_per_other'));
			$template->assign('otherItems',$articleotherItems);
			
			// Navigation
			$objectCate = $fengshuiCategories->getObject($objectItem->getCatId(),'id');
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