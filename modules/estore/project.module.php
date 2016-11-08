<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = "product.tpl.html";
# Return value extension
include_once(ROOT_PATH.'classes/dao/extends.class.php');
$extend = new Extend();

# ID = 6 Is constant of position Index left content
$extendInfo = $extend->getObject($id=2);
if($extendInfo) $template->assign('extendProject',$extendInfo);


# Get list project
$listItems = $productCategories->getObjects(1,"`status`=1",array("position"=>"ASC"),6);
if($listItems) $template->assign('listItems',$listItems);

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName($lang), 'url' => '/', 'current' => '0');

# Return menu left
$url="/projects.html";
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
				  $navigationItems[] = array('name' => $menuInfo2->getName(), 'url' =>$menuInfo2->getUrl(), 'current' => '0');
			  }
		  
	        }
	     $navigationItems[] = array('name' => $menuInfo1->getName($lang), 'url' =>$menuInfo1->getUrl(), 'current' => '0');
	     }
    
    }
$navigationItems[] = array('name' => $menuInfo->getName($lang), 'url' =>$menuInfo->getUrl(), 'current' => '0');
$template->assign('activeM',$id);
}


# Return array navigation
$template->assign('navigationItems',$navigationItems);

# Show Sub footer
$template->assign('subFooter',1);

# Active menu
$template->assign('mId',3);
?>