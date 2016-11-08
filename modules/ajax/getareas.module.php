<?php
/***************************************************************
Module Comment
Coder: Phantom
****************************************************************/
include_once(ROOT_PATH.'classes/dao/areas.class.php');
$areas = new Areas(1);
$id = $request->element('id');
$list=$areas->generateComboH('',$id);

if($list) echo $list;
else echo '';

?>