<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = "questions.tpl.html";
include_once(ROOT_PATH.'classes/dao/questions.class.php');
$questions = new Questions();

# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
# Generate the navigation bar
$navigationItems[] = array('name' => $messages['q&a'], 'url' => '#', 'current' => '0');
# Question
$page = $request->element('pg');
		if(!$page) $page = 1;
		
		# Build filters
		$condition = "`status` = '1'";	
		$question_per_page = 6;
		$rowsPages = $questions->getNumItems('id', $condition,$question_per_page);
		$template->assign('rowsPages',$rowsPages);
		if($page < 1) $page = 1;
		if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
		$listNew = $questions->getObjects($page,$condition,array('id'=>'ASC'),$question_per_page);
		$template->assign('listItems',$listNew);

		/// start page 
		$end = $page * $question_per_page;
		if($end > $question_per_page) $start  = $end -  $question_per_page +1;
		else $start = 1;
		if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
		$template->assign('end',$end);
		$template->assign('start',$start);
		/// end page
		$url = "/$lang/questions/page-%d.html";
		$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
		$template->assign('pager',$pager);
		
	
		
		# Page title, keywords, description
		$pageTitle = '$cateObject->getName($lang)';
		$pageKeywords = '$cateObject->getKeyword($lang)';
		$pageDescription = '$cateObject->getSapo($lang)';
        
        
$template->assign('navigationItems',$navigationItems);

# Show footer sub
$template->assign('subFooter',1);
?>