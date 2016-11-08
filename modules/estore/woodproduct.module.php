<?php
/*************************************************************************
Coder: PhanTom                           
**************************************************************************/
$templateFile = 'woodproduct.tpl.html';

include_once(ROOT_PATH.'classes/dao/woodshopcategories.class.php');
$woodshopCategories = new WoodshopCategories($storeId);

# Sort, current page
$sort = array("date_complete" =>"ASC");
$page = $request->element('pg');
if(!$page) $page = 1;

# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active products

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
		
# Get the Woodproduct list
$estore->setProperty('products_per_page', 9);
$rowsPages = $woodshopCategories->getNumItems('id', $condition,$estore->getProperty('products_per_page'));
$template->assign('rowsPages',$rowsPages);

# Start page 
$end = $page * $estore->getProperty('products_per_page');
if($end > $estore->getProperty('products_per_page')) $start  = $end -  $estore->getProperty('products_per_page') +1;
else $start = 1;
if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
$template->assign('end',$end);
$template->assign('start',$start);
# End page
if($page < 1) $page = 1;
if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
$woodProducts = $woodshopCategories->getObjects($page,$condition,$sort,$estore->getProperty('products_per_page'));
if($woodProducts) $template->assign('woodProducts',$woodProducts);

# Generate pager
$url = "/$lang/wood-products/page-%d.html";

$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
$template->assign('pager',$pager);

# Page title, keywords, description
$pageTitle = '';
$pageKeywords = '';
$pageDescription = '';

# Return menu left
$url="/wood-products.html";
$id=$menus->getIdFromUrl($url);
if($id){
   $menuInfo=$menus->getObject($id);
   if($menuInfo->getParentId()){
	  if($menuInfo->getParentId()==2) $template->assign('activeP',$id);
     $menuInfo1=$menus->getObject($menuInfo->getParentId());
        if($menuInfo1){
	      if($menuInfo1->getParentId()){
			  if($menuInfo1->getParentId()==2) $template->assign('activeP',$menuInfo1->getId());
		      $menuInfo2=$menus->getObject($menuInfo1->getParentId());
			  if($menuInfo2){
				  if($menuInfo2->getParentId()==2) $template->assign('activeP',$menuInfo2->getId());
				  $navigationItems[] = array('name' => $menuInfo2->getName($lang), 'url' =>$menuInfo2->getUrl($lang), 'current' => '0');
			  }
		  
	        }
	     $navigationItems[] = array('name' => $menuInfo1->getName($lang), 'url' =>$menuInfo1->getUrl($lang), 'current' => '0');
	     }
    $navigationItems[] = array('name' => $menuInfo->getName($lang), 'url' =>$menuInfo->getUrl($lang), 'current' => '0');
    }
$template->assign('activeM',$id);
}

# Return menu left
$menuLeft=$menus->getObject($id=2);
if($menuLeft) $template->assign('menuLeft',$menuLeft);
# Return navigation 
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Show name module
$template->assign('nameModule',$messages['products']);
# Active menu
$template->assign('mId',2);
?>