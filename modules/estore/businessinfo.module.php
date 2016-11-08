<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'businessinfo.tpl.html';
include_once(ROOT_PATH.'classes/dao/businesss.class.php');
include_once(ROOT_PATH.'classes/dao/materials.class.php');
include_once(ROOT_PATH.'classes/dao/materialcategories.class.php');
$materials = new Materials($sId);
$businesss = new Businesss($sId);
$materialcategories = new MaterialCategories($sId);

# Try to get category id & slug, then get the CategoryInfo object
$pId = $request->element('id');
$slug = $request->element('slug');
$template->assign('pId',$pId);
$cateslug = $request->element('catslug');
# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active product
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');
# Get the product info
$estore->setProperty('materials_per_other','');
$productItem = $businesss->getObject($pId);

if($productItem) {
	if(!$slug || in_array($slug,array('0','index',$productItem->getSlug()))) {
		
		
		$listNew = $materials->getObjects(1,"status=1 and `brand` = '$pId'",array("position"=>"DESC"),'');
		$template->assign('otherItems',$listNew);
		# Assign to the template
		$template->assign('item',$productItem);
		
		# Page title, keywords, description
		$pageTitle = $productItem->getFullName($lang);
		$pageKeywords = $productItem->getFullName($lang);
		$pageDescription = $productItem->getFullName($lang);
		# Navigation
		$navigationItems[] = array('name' => $productItem->getFullName($lang), 'url' => '', 'current' => '1');
		
	} else {	# Product id is ok but the slug is not valid
		$productItem = '';
		$templateFile = '404.tpl.html';
	}
}else $templateFile = '404.tpl.html';

# Navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>