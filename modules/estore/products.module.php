<?php
/*************************************************************************
Coder: PhanTom                          
**************************************************************************/
include_once(ROOT_PATH.'classes/dao/productcate.class.php');
$productCate = new ProductCate();
$templateFile = 'products.tpl.html';

# Get keywords, sort key, sort direction, current page
$sort_key = $request->element('sk');
if(!$sort_key) $sort_key = 'position';
$sort_direction = $request->element('sd');
if(!$sort_direction) $sort_direction = 'asc';
$cId = $request->element('cid',0);
$template->assign('cId',$cId);
$slug = $request->element('slug');
$page = $request->element('pg');
if(!$page) $page = 1;

# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active products

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');
# Product list		
$productCateInfo = $productCategories->getObject($cId);	
if($productCateInfo){
	if(!$slug || in_array($slug,array('0','index',$productCateInfo->getSlug()))) {
	
	    $allCid = $productCategories->getAllSubCategory($cId);
		
		//$allId=$productCate->getListProduct($cId);
		
		//if($allId) $condition .= " OR `id` in ($allId)";
		
	    if($allCid) $condition .= " and `cat_id` in ($allCid)";
		else $condition .= " and `cat_id`='$cId'";
		$parentId=$productCateInfo->getParentId();
		$template->assign('parentId',$parentId);
		$template->assign('productCateInfo',$productCateInfo);
		
		//
		$leftCate = $productCategories->getObjects(1,"`status`=1 and `parent_id`='$parentId'",array('position'=>'DESC'),20);
		if($leftCate ) $template->assign('leftCate',$leftCate);
		
		$sort = array($sort_key => $sort_direction);
		
		# Get the product list
		$estore->setProperty('products_per_page', 16);
		$rowsPages = $products->getNumItems('id', $condition,$estore->getProperty('products_per_page'));
		$template->assign('rowsPages',$rowsPages);
		/// start page 
		$end = $page * $estore->getProperty('products_per_page');
		if($end > $estore->getProperty('products_per_page')) $start  = $end -  $estore->getProperty('products_per_page') +1;
		else $start = 1;
		if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
		$template->assign('end',$end);
		$template->assign('start',$start);
		/// end page
		if($page < 1) $page = 1;
		if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
		$productItems = $products->getObjects($page,$condition,$sort,$estore->getProperty('products_per_page'));
		if($productItems) $template->assign('productItems',$productItems);
		
		# Generate pager
		$url = "/$lang/san-pham/$slug-c$cId/page-%d.html";
	
		$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
		$template->assign('pager',$pager);
		# Navigation
		$pCategory = $productCategories->getObject($productCateInfo->getParentId(), 'id', "`status` = '1'");
		if($pCategory) $navigationItems[] = array('name' => $pCategory->getName($lang), 'url' => $pCategory->getUrl($lang), 'current' => '0');
		$navigationItems[] = array('name' => $productCateInfo->getName($lang), 'url' => $productCateInfo->getUrl($lang), 'current' => '0');
		
		# Page title, keywords, description
		$pageTitle = $productCateInfo->getName($lang);
		$pageKeywords = $productCateInfo->getKeyword($lang);
		$pageDescription = $productCateInfo->getSapo($lang);
	}else $templateFile = '404.tpl.html';
}

# Navigation 
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Show name module
$template->assign('nameModule',$messages['projects']);

# Active menu
$template->assign('mId',3);
?>