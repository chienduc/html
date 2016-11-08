<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = "newdetail.tpl.html";

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Get new default

#article
$aId = $request->element('id'); 
$cateslug = $request->element('cateslug');
$slug = $request->element('slug');
$estore->setProperty('articles_per_other', 5);
if ($aId){
	$objectItem = $articles->getObject($aId); 
	if($objectItem){
		if((!$slug || in_array($slug,array('0','index',$objectItem->getSlug()))) && ( !$cateslug || in_array($cateslug,array('0','index',$objectItem->getCatSlug())))) {
			$template->assign('objectItem',$objectItem);
			$cateId = $objectItem->getCatId();
			
			$pCateId = $articleCategories->getParentCategory($cateId); 
			## other article
			$articleotherItems = $articles->getObjects(1,"status=1 AND cat_id= '$cateId' AND id <> $aId",array('date_created'=>'DESC'),$estore->getProperty('articles_per_other'));
			$template->assign('otherItems',$articleotherItems);
			
			// Navigation
			$objectCate = $articleCategories->getObject($objectItem->getCatId(),'id');
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