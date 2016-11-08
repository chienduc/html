<?php
/***************************************************************
Module Comment
Coder: Phantom
****************************************************************/
include_once(ROOT_PATH.'classes/dao/requests.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$requests = new Requests();


$data = array('name' => Filter($request->element('name')),
			  'email' => $request->element('email'),
			  'answer' => $request->element('detail'),
			  'id_service' => $request->element('id_service'),
			  'tel' => $request->element('tel'),
			  'status' =>  1,
			  'created' => date("Y-m-d H:i:s"));
$newId = $requests->addData($data);
if(isset($_SESSION[session_id()])) unset($_SESSION[session_id()]);
if($newId) echo 1;

?>