<?php
/*************************************************************************
Coder: PhanTom
*******************************************/
if(!$act) $act = 'campaign';
if($act=='uploadfile') $mod='add';
if(!$mod) $mod = 'list';
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '');
include_once(ROOT_PATH.'modules/admin/newsletter/'.strtolower($act).strtolower($mod).'.module.php');
?>