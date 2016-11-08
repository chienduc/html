<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
#Sitemap Page
$templateFile = "sitemap.tpl.html";

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['sitemap'], '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Menu
$listMenu = $menus->getObjects(1,"`status`=1 AND `mc_id`=2 AND `parent_id`=0",array("position"=>"ASC"),10);
if($listMenu) $template->assign('listMenu',$listMenu);

# Categories News
include_once(ROOT_PATH.'classes/dao/articlecategories.class.php');
$articleCategories = new ArticleCategories($sId);
$listCategories = $articleCategories->getObjects(1,"`status` = 1 AND `parent_id` = 0",array('position' => 'ASC'),5);
if($listCategories) $template->assign('listCategories',$listCategories);

# Page title, keywords, description
$pageTitle = $messages['sitemap'];

# Show footer sub
$template->assign('subFooter',1);

?>