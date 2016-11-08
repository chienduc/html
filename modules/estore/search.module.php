<?php
/*************************************************************************
Module search 
----------------------------------------------------------------
DerCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Coder: Tran Thi My Xuyen
Email: info@derasoft.com                                    
Last updated: 06/07/2012
**************************************************************************/
$templateFile = 'search.tpl.html';

# Get keywords, sort key, sort direction, current page
$keywords = $request->element('keywords','');
$page = $request->element('pg');
if(!$page) $page=1;


if($keywords=='Nhập từ khóa...') $keywords='';
if($_POST){
 $_SESSION['keywords']=$keywords;		
}

if(isset($_SESSION['keywords'])) $keywords=$_SESSION['keywords'];

# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active products

$condition .= $keywords?" AND (`slug` LIKE '%$keywords%' OR `keyword` LIKE '%$keywords%' OR `description` LIKE '%$keywords%' OR `name` LIKE '%$keywords%')":'';


$sort = array('position' => 'DESC');




# Get the product list
$estore->setProperty('products_per_page', 12);
$products = new Products($sId);
$productItems = $products->getObjects($page,$condition,$sort,$estore->getProperty('products_per_page'));
$rowsPages = $products->getNumItems('id', $condition,$estore->getProperty('products_per_page'));
if($productItems) $template->assign('productItems',$productItems);
$template->assign('rowsPages',$rowsPages);
/// start page 
$end = $page * $estore->getProperty('products_per_page');
if($end > $estore->getProperty('products_per_page')) $start  = $end -  $estore->getProperty('products_per_page') +1;
else $start = 1;
if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
else $end = $rowsPages['rows'];
$template->assign('end',$end);
$template->assign('start',$start);
/// end page

# Assign the variables to template
$template->assign('keywords',$keywords);


# Generate pager
$url = "/search/p-%d.html";
$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
$template->assign('pager',$pager);


# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['search'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
$pageTitle = $messages['search'];
?>