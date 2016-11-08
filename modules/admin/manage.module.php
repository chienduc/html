<?php
/*************************************************************************
Admin manage module
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 16/07/2008
**************************************************************************/
checkPermission(array(1,2,3));
if(!$act) $act = 'index';
if($act=='service' && !$mod) $mod='listcategory';
if(!$mod) $mod = 'list';

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '');
include_once(ROOT_PATH.'modules/admin/manage/'.strtolower($act).strtolower($mod).'.module.php');
?>