<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = "question.tpl.html";
include_once(ROOT_PATH.'classes/dao/questions.class.php');
$questions = new Questions();

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Generate the navigation bar
$navigationItems[] = array('name' => $messages['q&a'], 'url' => '/questions.html', 'current' => '0');
# Question
$aId = $request->element('id'); 
$slug = $request->element('slug');
$estore->setProperty('question_per_other', 5);
if ($aId){
	$objectItem = $questions->getObject($aId); 
	if($objectItem){
		if((!$slug || in_array($slug,array('0','index',$objectItem->getSlug())))) {
			$template->assign('objectItem',$objectItem);
			# Other question
			$listQuestion = $questions->getObjects(1,"status=1 AND `id` <> $aId",array('position'=>'DESC'),$estore->getProperty('question_per_other'));
			$template->assign('otherItems',$listQuestion);
			
			$navigationItems[] = array('name' => $objectItem->getTitle($lang), '', 'current' => '1');
			# Page title, keywords, description
			$pageTitle = $objectItem->getTitle($lang);
			$pageKeywords = $objectItem->getTitle($lang);
			$pageDescription = $objectItem->getTitle($lang);
		}
	}
}
# Navigation
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>