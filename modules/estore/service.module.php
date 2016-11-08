<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'service.tpl.html';

// Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');

#get the news list
$estore->setProperty('new_per_page', 5);
$serviceCategories = $serviceCategories->getObjects(1,"`status` = 1",array('position' => 'ASC'),10);
$template->assign('serviceCategories',$serviceCategories);

 
# Get new default
$navigationItems[] = array('name' =>  $messages['services'], 'url' => "/$lang/services.html", 'current' => '0');

# Get navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>