<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'material.tpl.html';

// Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');

#get the news list
include_once(ROOT_PATH.'classes/dao/materialcategories.class.php');
$materialcategories = new MaterialCategories($sId);
$listCategories = $materialcategories->getObjects(1,"`status` = 1 AND `parent_id` = 0",array('position' => 'ASC'),20);
 $template->assign('listCategories',$listCategories);
# Get new default
$navigationItems[] = array('name' =>  $messages['materials'], 'url' => "/$lang/materials.html", 'current' => '0');

# Get navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>