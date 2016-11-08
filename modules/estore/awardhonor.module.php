<?php
/*************************************************************************
Coder: PhanTom                           
**************************************************************************/
$templateFile = 'awardhonor.tpl.html';

include_once(ROOT_PATH.'classes/dao/awards.class.php');
$awards = new Awards($storeId);

# Sort, current page
$sort = array("name" =>"ASC");
$page = $request->element('pg');
if(!$page) $page = 1;

# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active products

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['awards-and-honors'], 'url' => '/', 'current' => '0');
		
# Get the Woodproduct list
$estore->setProperty('award_per_page', 9);
$rowsPages = $awards->getNumItems('id', $condition,$estore->getProperty('award_per_page'));
$template->assign('rowsPages',$rowsPages);

# Start page 
$end = $page * $estore->getProperty('award_per_page');
if($end > $estore->getProperty('award_per_page')) $start  = $end -  $estore->getProperty('award_per_page') +1;
else $start = 1;
if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
$template->assign('end',$end);
$template->assign('start',$start);
# End page
if($page < 1) $page = 1;
if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
$listAward = $awards->getObjects($page,$condition,$sort,$estore->getProperty('award_per_page'));
if($listAward) $template->assign('listAward',$listAward);

# Generate pager
$url = "/$lang/awards-and-honors/page-%d.html";

$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
$template->assign('pager',$pager);

# Page title, keywords, description
$pageTitle = '';
$pageKeywords = '';
$pageDescription = '';



# Return navigation 
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Show name module
$template->assign('nameModule',$messages['awards-and-honors']);

?>