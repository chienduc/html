<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/


$templateFile = 'index.tpl.html';


# Generate the navigation bar
$navigationItems[] = array('name' => $messages['index'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);


# Return list project set home
$estore->setProperty('project_index_item', 8);
$productSethome = $products->getObjects(1,"status = 1 AND `home`=1",array('position'=>'ASC'),$estore->getProperty('project_index_item'));
$template->assign('productSethome',$productSethome);


$productPromotion = $products->getObjects(1,"status = 1 AND `market_price`>0",array('market_price'=>'ASC'),$estore->getProperty('project_index_item'));
$template->assign('productPromotion',$productPromotion);

$productNew = $products->getObjects(1,"status = 1",array('created'=>'ASC'),$estore->getProperty('project_index_item'));
$template->assign('productNew',$productNew);



#include static item 
include_once(ROOT_PATH.'modules/estore/plugin/static.plugin.php');

$pageTitle = $estore->getProperty('title_index');

# Active menu
$template->assign('mId',1);
?>