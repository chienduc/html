<?php
/*************************************************************************
10 minutes crontab
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 01/06/2012
Coder: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
error_reporting(9);
if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', dirname(__FILE__).'/');
}

include_once(ROOT_PATH.'includes/config.inc.php');
include_once(ROOT_PATH.'includes/constant.inc.php');
include_once(ROOT_PATH.'includes/functions.inc.php');
include_once(ROOT_PATH.'classes/database/mysql.class.php');

# Database connection
$db = new DB();

$duration = 10;		# How long this crontab run (in minute)
$key = isset($_REQUEST['app_key'])?$_REQUEST['app_key']:'';
if($key != APP_KEY) {	# APP_KEY is wrong
	die("Invalid app key...");
} else {	# OK, run the cron job
	echo "Run time ".date("Y-m-d H:i:s").":<ol>";

	# Clean the session expired users list
	$time = date("Y-m-d H:i:s",time()-60*SESSION_TIME);
	$sql = "DELETE FROM ".DB_PREFIX."estore_online_users WHERE last_updated < '$time'";
	$db->query($sql);
	echo "<li>Expired users list - Cleanning success...</li>";

	# Update the statistics
	$storeId = 1;
	$now = date("Y-m-d H:i:s");
	$yesterday = date("Y-m-d 00:00:00", time() - 86400);
	$session = date("Y-m-d H:i:s", time() - SESSION_TIME*60);
	$today = date("Y-m-d 00:00:00");
	$month = date("Y-m-01 00:00:00");
	$year = date("Y-01-01 00:00:00");
	$sql = "SELECT count(id) FROM `".DB_PREFIX."products` WHERE store_id = '$storeId' AND `status`='1'";
	$results = $db->query($sql);
	$row=mysql_fetch_row($results);
	$tp = $row[0];
	$results = $db->query($sql." AND `created` > '$month'");
	$row=mysql_fetch_row($results);
	$tpim = $row[0];
	$sql = "SELECT count(id) FROM `".DB_PREFIX."orders` WHERE store_id = '$storeId'";
	$results = $db->query($sql." AND `created` > '$year'");
	$row=mysql_fetch_row($results);
	$oiy = $row[0];
	$results = $db->query($sql." AND `created` > '$month'");
	$row=mysql_fetch_row($results);
	$oim = $row[0];
	$sql = "SELECT SUM(oi.quantity*oi.price) FROM `".DB_PREFIX."order_items` oi, `".DB_PREFIX."orders` o WHERE o.id=oi.order_id AND o.status=1 AND o.store_id = '$storeId'";
	$results = $db->query($sql." AND `created` > '$year'");
	$row=mysql_fetch_row($results);
	$riy = $row[0];
	$results = $db->query($sql." AND `created` > '$month'");
	$row=mysql_fetch_row($results);
	$rim = $row[0];
	$sql = "SELECT count(id) FROM `".DB_PREFIX."articles` WHERE store_id = '$storeId'";
	$results = $db->query($sql);
	$row=mysql_fetch_row($results);
	$te = $row[0];
	$results = $db->query($sql." AND `date_created` > '$month'");
	$row=mysql_fetch_row($results);
	$eim = $row[0];
	
	# Check if we should reset the hit counter
	$sql = "SELECT him,hid,reset_d,reset_m FROM `".DB_PREFIX."estore_statistics` WHERE id = '$storeId'";
	$results = $db->query($sql);
	$row=mysql_fetch_row($results);
	$him = $row[0];
	$hid = $row[1];
	$reset_d = $row[2];
	$reset_m = $row[3];
	
	$counter = '';
	# Hits today
	if(date("H")=='01' && !$reset_d) {
		$counter .= "hid='0',reset_d='1',";
		echo "<li>Reset today counter successfully...</li>";
	}
	if(date("H") == '02') $counter .= "reset_d='0',";
	
	# Hits this month
	if(date("d") == '01' && !$reset_m) {
		$counter .= "him='0',reset_m='1',";
		echo "<li>Reset month counter successfully...</li>";
	}
	if(date("d") == '02') $counter .= "reset_m='0',";

	# Update statistics
	$db->query("UPDATE `".DB_PREFIX."estore_statistics` SET tp='$tp',tpim='$tpim',oiy='$oiy',oim='$oim',riy='$riy',rim='$rim',te='$te',eim='$eim',".($counter?$counter:'')."last_updated='".date("Y-m-d H:i:s")."' WHERE id='$storeId'");
	echo "<li>Update the summary successfully...</li>";

	echo "</ol>";
}

# Close database connection
$db->close();
?>