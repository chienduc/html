<?php
/*************************************************************************
Coder: PhanTom                          
**************************************************************************/

$templateFile = 'materials.tpl.html';
include_once(ROOT_PATH.'classes/dao/materials.class.php');
include_once(ROOT_PATH.'classes/dao/materialcategories.class.php');
$materials = new Materials($sId);
$materialcategories = new MaterialCategories($sId);
# Get keywords, sort key, sort direction, current page
$sort_key = $request->element('sk');
if(!$sort_key) $sort_key = 'created';
$sort_direction = $request->element('sd');
if(!$sort_direction) $sort_direction = 'desc';
$cId = $request->element('cid',0);
$template->assign('cId',$cId);
$slug = $request->element('slug');
$page = $request->element('pg');
if(!$page) $page = 1;

# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active materials

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['materials'], 'url' =>"/$lang/materials.html", 'current' => '0');
# Product list		
$materialCateInfo = $materialcategories->getObject($cId);	
if($materialCateInfo){
	if(!$slug || in_array($slug,array('0','index',$materialCateInfo->getSlug()))) {
	    $allCid = $productCategories->getAllSubCategory($cId);
	    if($allCid) $condition .= " and `cat_id` in ($allCid)";
		else $condition .= " and `cat_id`='$cId'";
		$template->assign('parentId',$materialCateInfo->getParentId());
		$template->assign('materialCateInfo',$materialCateInfo);
		
		$sort = array($sort_key => $sort_direction);
		
		# Get the product list
		$estore->setProperty('materials_per_page', 12);
		$rowsPages = $materials->getNumItems('id', $condition,$estore->getProperty('materials_per_page'));
		$template->assign('rowsPages',$rowsPages);
		/// start page 
		$end = $page * $estore->getProperty('materials_per_page');
		if($end > $estore->getProperty('materials_per_page')) $start  = $end -  $estore->getProperty('materials_per_page') +1;
		else $start = 1;
		if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
		$template->assign('end',$end);
		$template->assign('start',$start);
		/// end page
		if($page < 1) $page = 1;
		if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
		$productItems = $materials->getObjects($page,$condition,$sort,$estore->getProperty('materials_per_page'));
		if($productItems) $template->assign('productItems',$productItems);
		
		# Generate pager
		$url = "/$lang/project/$slug-c$cId/page-%d.html";
	
		$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
		$template->assign('pager',$pager);
		# Navigation
		$pCategory = $productCategories->getObject($materialCateInfo->getParentId(), 'id', "`status` = '1'");
		if($pCategory) $navigationItems[] = array('name' => $pCategory->getName($lang), 'url' => $pCategory->getUrl($lang), 'current' => '0');
		$navigationItems[] = array('name' => $materialCateInfo->getName($lang), 'url' => $materialCateInfo->getUrl($lang), 'current' => '0');
		
		# Page title, keywords, description
		$pageTitle = $materialCateInfo->getName($lang);
		$pageKeywords = $materialCateInfo->getKeyword($lang);
		$pageDescription = $materialCateInfo->getSapo($lang);
	}else $templateFile = '404.tpl.html';
}

# Navigation 
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Show name module
$template->assign('nameModule',$messages['materials']);

# Active menu
$template->assign('mId',3);
?>