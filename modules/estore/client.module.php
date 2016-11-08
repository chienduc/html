<?php
/*************************************************************************
Coder: PhanTom                           
**************************************************************************/
$templateFile = 'clients.tpl.html';

include_once(ROOT_PATH.'classes/dao/clients.class.php');
$clients = new Clients($storeId);

# Sort, current page
$sort = array("name" =>"ASC");
$page = $request->element('pg');
if(!$page) $page = 1;

# Build filters
$condition = "`status` = '1'";		# Front-end, so only get active products

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');
		
# Get the Woodproduct list
$estore->setProperty('client_per_page', 9);
$rowsPages = $clients->getNumItems('id', $condition,$estore->getProperty('client_per_page'));
$template->assign('rowsPages',$rowsPages);

# Start page 
$end = $page * $estore->getProperty('client_per_page');
if($end > $estore->getProperty('client_per_page')) $start  = $end -  $estore->getProperty('client_per_page') +1;
else $start = 1;
if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
$template->assign('end',$end);
$template->assign('start',$start);
# End page
if($page < 1) $page = 1;
if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
$listClients = $clients->getObjects($page,$condition,$sort,$estore->getProperty('client_per_page'));
if($listClients) $template->assign('listClients',$listClients);

# Generate pager
$url = "/$lang/clients/page-%d.html";

$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
$template->assign('pager',$pager);

# Page title, keywords, description
$pageTitle = '';
$pageKeywords = '';
$pageDescription = '';

# Return menu left
$url="/clients.html";
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
    }
 $navigationItems[] = array('name' => $menuInfo->getName($lang), 'url' =>$menuInfo->getUrl($lang), 'current' => '0');
$template->assign('activeM',$id);
}

# Return navigation 
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Active menu
$template->assign('mId',5);
?>