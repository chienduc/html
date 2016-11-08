<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/

$templateFile = "servicedetail.tpl.html";
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Get new default
$navigationItems[] = array('name' =>  $messages['services'], 'url' => "/$lang/services.html", 'current' => '0');

$listCateServices = $serviceCategories->getObjects(1,"`status`=1 AND `parent_id`=0",array("position"=>"DESC"),100);
if($listCateServices) $template->assign('listCateServices',$listCateServices);


#article
$aId = $request->element('id'); 
$cateslug = $request->element('cateslug');
$slug = $request->element('slug');
$estore->setProperty('services_per_other', 5);

# Comment
$listComment = $comments->getObjects(1,"`status`=1 AND `id_type`= '$aId' AND `pid`=0",array("created"=>"DESC"),100);
if($listComment) $template->assign('listComment',$listComment);
$numcomemnt=$comments->getNumComment($aId);
if($numcomemnt) $template->assign('numcomemnt',$numcomemnt);

#-- Combo service
$comboService=$services->generateCombo($aId);
if($comboService) $template->assign('comboService',$comboService);


#-- ckEditor
$template->assign('ckEditor',1);

if ($aId){
	$objectItem = $services->getObject($aId); 
   
	if($objectItem){
		if((!$slug || in_array($slug,array('0','index',$objectItem->getSlug()))) && ( !$cateslug || in_array($cateslug,array('0','index',$objectItem->getCatSlug())))) {
			$template->assign('objectItem',$objectItem);
			$cateId = $objectItem->getCatId();
			$services->updateData(array('viewed'=>$objectItem->getViewed() + 1 ),$aId);
			
			$pCateId = $serviceCategories->getParentCategory($cateId); 
			
			## other article
			$articleotherItems = $services->getObjects(1,"status=1 AND cat_id= '$cateId' AND id <> $aId",array('date_created'=>'DESC'),$estore->getProperty('services_per_other'));
			$template->assign('otherItems',$articleotherItems);
			
			// Navigation
			$objectCate = $serviceCategories->getObject($objectItem->getCatId(),'id');
           
			if($objectCate){
			  $cateObjects = $serviceCategories->getObject($objectCate->getParentId(),'id');
                           
				if($cateObjects){  
		               $template->assign('cateS',$cateObjects->getId());
                                              
		               $navigationItems[] = array('name' =>  $cateObjects->getName($lang), 'url' => $cateObjects->getUrl($lang), 'current' => '0');
		               }else $template->assign('cateS',$objectCate->getId());
                                              
				
				$navigationItems[] = array('name' => $objectCate->getName($lang), 'url' => $objectCate->getUrl($lang), 'current' => '0');
			}
			$navigationItems[] = array('name' => $objectItem->getTitle($lang), '', 'current' => '1');
			# Page title, keywords, description
			$pageTitle = $objectItem->getTitle($lang);
			$pageKeywords = $objectItem->getKeyword($lang);
			$pageDescription = $objectItem->getSapo($lang);
		}else $templateFile = '404.tpl.html';
	}else $templateFile = '404.tpl.html';
}


# Navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);

# Comment
$template->assign('type',C_SERVICE);
$template->assign('id',$aId);
$template->assign('user_id','');
$template->assign('user_type','');
?>