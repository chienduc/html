<?php
/*************************************************************************
Index page
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 06/19/2010
Coder: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
$time_start = microtime(true);

error_reporting(9);
if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', dirname(__FILE__).'/');
}
include_once(ROOT_PATH.'classes/bootstrap.php');
include_once(ROOT_PATH.'includes/config.inc.php');
include_once(ROOT_PATH.'includes/constant.inc.php');
include_once(ROOT_PATH.'classes/database/mysql.class.php');
include_once(ROOT_PATH.'classes/dao/users.class.php');
include_once(ROOT_PATH.'classes/dao/storeusers.class.php');

# Database connection
$db = new DB();

# Users object
$users = new Users();
$store_users = new StoreUsers();

if($store_users->checkDuplicate($_GET['username']))
	echo "0";
else
	echo "1";

# Close database connection
$db->close();

$time_end = microtime(true);
$time = $time_end - $time_start;
/*echo '<center>'.$time.'</center>';*/
?>