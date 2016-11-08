<?php
/***************************************************************
Module Question
Coder: Phantom
****************************************************************/
include_once(ROOT_PATH.'classes/dao/questions.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$questions = new Questions();

# Get value
$tel = $request->element('tel');
# Check if duplicate slug
$slug = TextFilter::urlize($request->element('detail'),false,'-');
$i = 0;
$dup = 1;
while($dup) {
	$dup = $questions->checkDuplicate($slug.($i?'-'.$i:''),'slug');
	if($dup) $i++;
}
$slug .= $i?'-'.$i:'';

$data = array('name' => Filter($request->element('name')),
			  'slug' => $slug,
			  'title' => Filter($request->element('detail')),
			  'email' => $request->element('email'),
			  'tel' => Filter($request->element('tel')),
			  'status' => 0,
			  'created' => date("Y-m-d H:i:s"));
$newId = $questions->addData($data);

if($newId) echo '1';

?>