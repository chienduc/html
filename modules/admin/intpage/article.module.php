<?php
/*************************************************************************
Internal pages listing module
----------------------------------------------------------------
Derasoft CMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Last updated: 13/05/2012
Coder: Mai Minh
**************************************************************************/
$templateFile = 'intpage.tpl.html';
include_once(ROOT_PATH.'classes/dao/articles.class.php');
include_once(ROOT_PATH.'classes/dao/articlecategories.class.php');
$articles = new Articles($storeId);
$articleCategories = new ArticleCategories($storeId);

$listTabs = array($amessages['list_article'] => '/'.ADMIN_SCRIPT.'?op=intpage&act=article');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',1);

# Get parameters
$items_per_page = $request->element('ipp')?$request->element('ipp'):DEFAULT_ADMIN_ROWS_PER_PAGE;
if($items_per_page) $template->assign('ipp',$items_per_page);
$page = $request->element('pg')?$request->element('pg'):1;
if($page) $template->assign('pg',$page);
$sort_key = $request->element('sk')?$request->element('sk'):'id';
if($sort_key) $template->assign('sk',$sort_key);
$sort_direction = $request->element('sd')?$request->element('sd'):'DESC';
if($sort_direction) $template->assign('sd',$sort_direction);
$do = $request->element('doo')?$request->element('doo'):'';
if($do) $template->assign('do',$do);
$kw = $request->element('kw')?$request->element('kw'):'';
if($kw) $template->assign('kw',$kw);
$pId = $request->element('pId','-1');
if($pId>=0) {
	$gfId = $articleCategories->getParentIdFromId($pId);
	$template->assign('pId',$pId);
	$template->assign('gfId',$gfId);
}

# Build WHERE condition
$condition = $pId>0?"`cat_id` = '$pId'":"1>0";
if($kw) $condition = "(`id`='$kw' OR `slug` LIKE '%$kw%' OR `title` LIKE '%$kw%' OR `sapo` LIKE '%$kw%')";
$pages_condition = "`store_id` = '$storeId' AND `status` = 1 AND ($condition)";
$sort = array($sort_key => $sort_direction);

# Page navigation
$rowsPages = $articles->getNumItems('id', $pages_condition,$items_per_page);
$template->assign('rowsPages',$rowsPages);
if($page < 1) $page = 1;
if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
$start_num = ($page-1)*$items_per_page+1;
$template->assign('startNum',$start_num);
$url = '/'.ADMIN_SCRIPT."?op=intpage&act=article&doo=$do&kw=$kw&lang=$lang&ipp=$items_per_page&sk=$sort_key&sd=$sort_direction&pId=$pId&pg=%d";
$pager = Url::genPager($url,$rowsPages['pages'],$page);
$template->assign('pager',$pager);

# Get objects
$listItems = $articles->getObjects($page,$condition,$sort,$items_per_page);
if($listItems) $template->assign('listItems',$listItems);

# Result code
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$error_code = $request->element('ecode');
if($error_code) $template->assign('error_code',$error_code);

# Link
$link = '/'.ADMIN_SCRIPT."?op=intpage&act=article&kw=$kw&lang=$lang&ipp=$items_per_page&sk=$sort_key&sd=$sort_direction&pId=$pId&pg=$page";
$template->assign('link',$link);

# Show URL Popup
$template->assign('urlPopup',1);

#bottom Action Combo
$categoryCombo = $articleCategories->generateCombo($pId,1);
if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);

if($_POST) {
	header('location:'.'/'.ADMIN_SCRIPT."?op=intpage&act=article&doo=$do&kw=$kw&lang=$lang&ipp=$items_per_page&sk=$sort_key&sd=$sort_direction&pg=$page&ecode=$error_code&rcode=$result_code&pId=$pId");
} else {

}
?>