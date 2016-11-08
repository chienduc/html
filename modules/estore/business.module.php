<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'business.tpl.html'; 

// Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');

#get the news list
$estore->setProperty('new_per_page', 5);
include_once(ROOT_PATH.'classes/dao/businesss.class.php');
$businesss = new Businesss($sId);
$listCategories = $businesss->getObjects(1,"`status` = 1",array('date_created' => 'ASC'),20);
 $template->assign('listCategories',$listCategories);
# Get new default
$navigationItems[] = array('name' =>  $messages['business'], 'url' => "/$lang/business.html", 'current' => '0');

# Get navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>