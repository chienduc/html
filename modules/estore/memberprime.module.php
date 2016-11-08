<?php
/*************************************************************************
Coder: PhanTom                           
**************************************************************************/
$templateFile = 'memberprime.tpl.html';

include_once(ROOT_PATH.'classes/dao/extends.class.php');
$extend = new Extend();

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');

$extendMember = $extend->getObjects(1,"`id` in (3,4,5)",array("position"=>"ASC"),3);
if($extendMember) $template->assign('extendMember',$extendMember);

# Page title, keywords, description
$pageTitle = '';
$pageKeywords = '';
$pageDescription = '';

# Return menu left
$url="/member-prime.html";
$id=$menus->getIdFromUrl($url);
if($id){
   $menuInfo=$menus->getObject($id);
   if($menuInfo->getParentId()){
	  if($menuInfo->getParentId()==4) $template->assign('activeP',$id);
     $menuInfo1=$menus->getObject($menuInfo->getParentId());
        if($menuInfo1){
	      if($menuInfo1->getParentId()){
			  if($menuInfo1->getParentId()==4) $template->assign('activeP',$menuInfo1->getId());
		      $menuInfo2=$menus->getObject($menuInfo1->getParentId());
			  if($menuInfo2){
				  if($menuInfo2->getParentId()==4) $template->assign('activeP',$menuInfo2->getId());
				  $navigationItems[] = array('name' => $menuInfo2->getName(), 'url' =>$menuInfo2->getUrl(), 'current' => '0');
			  }
		  
	        }
	     $navigationItems[] = array('name' => $menuInfo1->getName(), 'url' =>$menuInfo1->getUrl(), 'current' => '0');
	     }
    
    }
$navigationItems[] = array('name' => $menuInfo->getName(), 'url' =>$menuInfo->getUrl(), 'current' => '0');
$template->assign('activeM',$id);
}

# Return menu left
$menuLeft=$menus->getObject($id=4);
if($menuLeft) $template->assign('menuLeft',$menuLeft);
# Return navigation 
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
# Active menu
$template->assign('mId',4);
?>