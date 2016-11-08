<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'productdetail.tpl.html';

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
$estore->setProperty('products_per_other','');
$productItem = $products->getObject($pId,'id',$condition);
if($productItem) {
	if(!$slug || in_array($slug,array('0','index',$productItem->getSlug())) && ( !$cateslug || in_array($cateslug,array('0','index',$productItem->getCatSlug())))) {
		# Get category
		$cCategory = $productCategories->getObject($productItem->getCatId(), 'id', "`status` = '1'");
		$cId = $productItem->getCatId();
		
		# Get the parent category, if available
		
		if($cCategory) {
			$template->assign('cId',$cCategory->getId());
			$template->assign('cCategory',$cCategory);
			$pCategory = $productCategories->getObject($cCategory->getParentId(), 'id', "`status` = '1'");
			if($pCategory) $navigationItems[] = array('name' => $pCategory->getName($lang), 'url' => $pCategory->getUrl($lang), 'current' => '0');
			$navigationItems[] = array('name' => $cCategory->getName($lang), 'url' => $cCategory->getUrl($lang), 'current' => '0');
			
		}
		# Assign to the template
		$template->assign('item',$productItem);
		
		# In Crease Viewed
		$products->increaseViewed($productItem->getViewed()+1,$pId);
		
		# Page title, keywords, description
		$pageTitle = $productItem->getName($lang);
		$pageKeywords = $productItem->getProperty('keywords');
		$pageDescription = $productItem->getDescription($lang);
		# Navigation
		$navigationItems[] = array('name' => $productItem->getName($lang), 'url' => '', 'current' => '1');
		
	   $productotherItems = $products->getObjects(1,"status=1 AND `cat_id` = '$cId' AND id <> $pId",array('created'=>'DESC'),$estore->getProperty('products_per_other'));
			$template->assign('productotherItems',$productotherItems);
		
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