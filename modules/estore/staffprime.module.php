<?php
/*************************************************************************
Coder: PhanTom                         
**************************************************************************/
$templateFile = 'staffprime.tpl.html';

include_once(ROOT_PATH.'classes/dao/staffcategories.class.php');
include_once(ROOT_PATH.'classes/dao/staff.class.php');
$staffCategories = new StaffCategories($storeId);
$staff = new Staff($storeId);

# Get keywords, sort key, sort direction, current page
$sort = array("name" => "ASC");
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
$staffCateInfo = $staffCategories->getObject($cId);
if($staffCateInfo){
	if(!$slug || in_array($slug,array('0','index',$staffCateInfo->getSlug()))) {
		$template->assign('staffCateInfo',$staffCateInfo);
		
		$condition .=" AND `cat_id`=$cId";
		# Get the product list
		$estore->setProperty('staff_per_page', 9);
		$rowsPages = $staff->getNumItems('id', $condition,$estore->getProperty('staff_per_page'));
		$template->assign('rowsPages',$rowsPages);
		# start page 
		$end = $page * $estore->getProperty('staff_per_page');
		if($end > $estore->getProperty('staff_per_page')) $start  = $end -  $estore->getProperty('staff_per_page') +1;
		else $start = 1;
		if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
		$template->assign('end',$end);
		$template->assign('start',$start);
		# end page
		if($page < 1) $page = 1;
		if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
		$staffItems = $staff->getObjects($page,$condition,$sort,$estore->getProperty('staff_per_page'));
		if($staffItems) $template->assign('staffItems',$staffItems);
		
		# Generate pager
		$url = "/$lang/staffs/$slug-c$cId/page-%d.html";
		$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
		$template->assign('pager',$pager);
		
		# Page title, keywords, description
		$pageTitle = $staffCateInfo->getName();
		$pageKeywords = $staffCateInfo->getKeyword();
		$pageDescription = $staffCateInfo->getSapo();
		
	   # Return menu left
		$url="/staffs/$slug-c$cId.html";
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
	}else $templateFile = '404.tpl.html';
}else $templateFile = '404.tpl.html';


# Return navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Show name module
$template->assign('nameModule',$messages['staff']);
# Active menu
$template->assign('mId',4);
?>