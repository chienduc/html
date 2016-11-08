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

# Get keywords, sort key, sort direction, current page
$sort_key = $request->element('sk');
if(!$sort_key) $sort_key = 'created';
$sort_direction = $request->element('sd');
if(!$sort_direction) $sort_direction = 'desc';
$page = $request->element('pg');
if(!$page) $page = 1;

# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active products
$sort = array($sort_key => $sort_direction);

# Get the product list
$estore->setProperty('products_per_page', 10);
$products = new Products($sId);
$rowsPages = $products->getNumItems('id', $condition,$estore->getProperty('products_per_page'));
$template->assign('rowsPages',$rowsPages);
if($page < 1) $page = 1;
if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
$productItems = $products->getObjects($page,$condition,$sort,$estore->getProperty('products_per_page'));
if($productItems) $template->assign('productItems',$productItems);

# support
include_once(ROOT_PATH.'classes/dao/support.class.php');
$support = new Support();
$supportItems = $support->getObjects(1,"status=1", array('position'=>'ASC'));
$template->assign("supportItems",$supportItems);

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['new_products'], 'url' => Url::genUrl('newproducts',''), 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Generate pager
$url = "/?act=newproducts&sk=$sort_key&sd=$sort_direction&pg=%d";
$pager = Url::genPager($url,$rowsPages['pages'],$page);
$template->assign('pager',$pager);

# Page title, keywords, description
$pageTitle = $messages['new_products'];
$template->assign('products',$products);
?>