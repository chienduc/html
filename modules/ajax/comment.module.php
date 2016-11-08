<?php
/***************************************************************
Module Comment
Coder: Phantom
****************************************************************/
include_once(ROOT_PATH.'classes/dao/comments.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$comments = new Comments();


$data = array('fullname' => Filter($request->element('user_name')),
			  'email' => $request->element('email'),
			  'details' => Filter($request->element('txtComment')),
			  'user_id' => $request->element('email'),
			  'id_type' => $request->element('id'),
			  'type' => $request->element('type'),
			  'user_type' => $request->element('user_type'),
			  'pid' =>  $request->element('id_comment'),
			  'status' =>  1,
			  'store_id' =>  1,
			  'created' => date("Y-m-d H:i:s"));
$newId = $comments->addData($data);

if($newId) echo '1';

?>