<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
include_once(ROOT_PATH.'classes/dao/static.class.php');
$templateFile = "introduction.tpl.html";
$statics = new StaticPage($sId);

#Get Static
$slug = $request->element('slug'); 

if ($slug){
	$aboutusItem = $statics->getObjectFromSlug($slug); 
	if($aboutusItem) $template->assign('aboutusItem',$aboutusItem);
	else $templateFile = '404.tpl.html';
}

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');

# Page title, keywords, description
if ($aboutusItem){  
	$pageTitle = $aboutusItem->getTitle($lang);
	$pageKeywords = $aboutusItem->getKeywords($lang);
	$pageDescription = $aboutusItem->getSapo($lang);
}
# Return menu left
$url="/introduction/".$slug.".html";
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
# Return array navigation
$template->assign('navigationItems',$navigationItems);

# Show Sub footer
$template->assign('subFooter',1);
# Active menu
$template->assign('mId',2);
?>