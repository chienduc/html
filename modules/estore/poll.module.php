<?php
include_once(ROOT_PATH."classes/dao/questions.class.php");
include_once(ROOT_PATH."classes/dao/answers.class.php");
$questions= new Questions();
$answers = new Answers();
$templateFile="poll.tpl.html";
if($_POST){
	$idAnswer=$request->element('answer');
	if($idAnswer){
		$answerObject= $answers->getObject($idAnswer);
		$answers->increaseViewed($answerObject->getCounts()+1,$idAnswer);
	}
		header("location:".$_SERVER['HTTP_REFERER']);
	}
?>