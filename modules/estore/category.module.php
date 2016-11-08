<?php
/*************************************************************************
Estore category module
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 06/19/2010
**************************************************************************/
include_once(ROOT_PATH.'classes/dao/products.class.php');
$templateFile = 'category.tpl.html';

if($cCategory) {		# Check if this cId is active
	# Get keywords, sort key, sort direction, current page
	$keywords = $request->element('kw');
	$sort_key = $request->element('sk');
	if(!$sort_key) $sort_key = 'created';
	$sort_direction = $request->element('sd');
	if(!$sort_direction) $sort_direction = 'desc';
	$page = $request->element('pg');
	if(!$page) $page = 1;
	
	# Build filters
	$condition = "`status` = '1'";		# Front-end, so only get active products
	$condition .= $cId?" AND `cat_id` = '$cId'":'';
	$condition .= $keywords?" AND (`slug` LIKE '%$keywords%' OR `name` LIKE '%$keywords%')":'';
	$sort = array($sort_key => $sort_direction);
	
	# Get the product list
	$estore->setProperty('products_per_page', 16);
	$products = new Products($sId);
	$rowsPages = $products->getNumItems('id', $condition,$estore->getProperty('products_per_page'));
	$template->assign('rowsPages',$rowsPages);
	if($page < 1) $page = 1;
	if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
	$productItems = $products->getObjects($page,$condition,$sort,$estore->getProperty('products_per_page'));
	if($productItems) $template->assign('productItems',$productItems);
	
	# Generate the navigation bar
	$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
	$navigationItems[] = array('name' => $messages['products'], 'url' => Url::genUrl('products',''), 'current' => '0');
	if($pCategory) 	$navigationItems[] = array('name' => $pCategory->getName(), 'url' => $pCategory->getUrl(), 'current' => '0');
	$navigationItems[] = array('name' => $cCategory->getName(), 'url' => '', 'current' => '1');
	$template->assign('navigationItems',$navigationItems);
	
	# Generate pager
	$url = "/?act=$act&cId=$cId&slug=$slug".($keywords?"&kw=$keywords":'')."&sk=$sort_key&sd=$sort_direction&pg=%d";
	$pager = Url::genPager($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
	$template->assign('pager',$pager);
	$template->assign('load_filters',1);
	
	# Page title, keywords, description
	$pageTitle = $cCategory->getName();
	$pageKeywords = $cCategory->getProperty('keywords');
	$pageDescription = $cCategory->getProperty('description');
} else {
	$templateFile = '404.tpl.html';
}
?>