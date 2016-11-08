<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'news.tpl.html';

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Get new default

$cId = $request->element('cid',0);
$slug = $request->element('slug');

# Get article category
include_once(ROOT_PATH.'classes/dao/articlecategories.class.php');
$articleCategories = new ArticleCategories($sId);
$cateObject = $articleCategories->getObject($cId,'id');

if($cateObject){
	if(!$slug || in_array($slug,array('0','index',$cateObject->getSlug()))) {
		$template->assign('cateObject',$cateObject);
		$cateId = $cateObject->getId();
		
		$pCateId = $articleCategories->getParentCategory($cateId); 
		# Get keywords, sort key, sort direction, current page
		$sort_key = $cateObject->getProperty('sort_type');
		if(!$sort_key) $sort_key = 'position';
		$sort_direction =$cateObject->getProperty('sort_dir');
		if(!$sort_direction) $sort_direction = 'asc';
		$page = $request->element('pg');
		if(!$page) $page = 1;
		
		# Build filters
		$condition = "`status` = '1'";		# Front-end, so only get active products
		if($cateId) $condition .= "and cat_id = '$cateId'";
		$sort = array($sort_key => $sort_direction);
		
		$article_per_page = $cateObject->getProperty('ipp');
		$rowsPages = $articles->getNumItems('id', $condition,$article_per_page);
		$template->assign('rowsPages',$rowsPages);
		if($page < 1) $page = 1;
		if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
		$listNew = $articles->getObjects($page,$condition,$sort,$article_per_page);
		$template->assign('listItems',$listNew);

		/// start page 
		$end = $page * $article_per_page;
		if($end > $article_per_page) $start  = $end -  $article_per_page +1;
		else $start = 1;
		if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
		$template->assign('end',$end);
		$template->assign('start',$start);
		/// end page
		$url = "/$lang/news/$slug-c$cId/page-%d.html";
		$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
		$template->assign('pager',$pager);
		
		$navigationItems[] = array('name' => $cateObject->getName($_SESSION['lang']), 'url' => $cateObject->getUrl($lang), 'current' => '0'); 
		
		# Page title, keywords, description
		$pageTitle = $cateObject->getName($lang);
		$pageKeywords = $cateObject->getKeyword($lang);
		$pageDescription = $cateObject->getSapo($lang);
	}
}

# Get Navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Show name module
$template->assign('nameModule',$messages['news']);
?>