<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'new.tpl.html';

// Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');

#get the news list
$estore->setProperty('new_per_page', 5);
include_once(ROOT_PATH.'classes/dao/articlecategories.class.php');
$articleCategories = new ArticleCategories($sId);
$listCategories = $articleCategories->getObjects(1,"`status` = 1 AND `parent_id` = 0",array('position' => 'ASC'),20);
 $template->assign('listCategories',$listCategories);
# Get new default
$navigationItems[] = array('name' =>  $messages['news'], 'url' => "/$lang/news.html", 'current' => '0');

# Get navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>