<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'services.tpl.html';

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Get new default
$navigationItems[] = array('name' =>  $messages['services'], 'url' => "/$lang/services.html", 'current' => '0');

$cId = $request->element('cid',0);
$slug = $request->element('slug');

$listCateServices = $serviceCategories->getObjects(1,"`status`=1 AND `parent_id`=0",array("position"=>"DESC"),100);
if($listCateServices) $template->assign('listCateServices',$listCateServices);
# Get article category
$cateObject = $serviceCategories->getObject($cId,'id');

if($cateObject){
	if(!$slug || in_array($slug,array('0','index',$cateObject->getSlug()))) {
		$template->assign('cateObject',$cateObject);
		$cateId = $cateObject->getId();
		
		$pCateId = $serviceCategories->getParentCategory($cateId); 
		$cateObjects = $serviceCategories->getObject($pCateId,'id');
		if($cateObjects){
		   $template->assign('cateS',$pCateId);
		   $navigationItems[] = array('name' =>  $cateObjects->getName($lang), 'url' => $cateObjects->getUrl($lang), 'current' => '0');
		 }else $template->assign('cateS',$cId);
		# Get keywords, sort key, sort direction, current page
		$sort_key = $cateObject->getProperty('sort_type');
		if(!$sort_key) $sort_key = 'date_created';
		$sort_direction =$cateObject->getProperty('sort_dir');
		if(!$sort_direction) $sort_direction = 'desc';
		$page = $request->element('pg');
		if(!$page) $page = 1;
		
        
        # Get All Parent Id
        $allId=$serviceCategories->getAllId($cateId);
		# Build filters
		$condition = "`status` = '1'";		# Front-end, so only get active products
		if($allId) $condition .= " and cat_id in ($allId)";
        else $condition='1<0';
		$sort = array($sort_key => $sort_direction);
		
		$article_per_page = $cateObject->getProperty('ipp');
		$rowsPages = $services->getNumItems('id', $condition,$article_per_page);
		$template->assign('rowsPages',$rowsPages);
		if($page < 1) $page = 1;
		if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
		$listNew = $services->getObjects($page,$condition,$sort,$article_per_page);
		$template->assign('listItems',$listNew);

		/// start page 
		$end = $page * $article_per_page;
		if($end > $article_per_page) $start  = $end -  $article_per_page +1;
		else $start = 1;
		if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
		$template->assign('end',$end);
		$template->assign('start',$start);
		/// end page
		$url = "/$lang/services/$slug-c$cId/page-%d.html";
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
$template->assign('nameModule',$messages['services']);
?>