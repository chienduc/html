<?php
/*************************************************************************
Dashboard module
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd                                  
Last updated: 27/12/2011
Coder: Mai Minh
**************************************************************************/
$templateFile = 'dashboard.tpl.html';
include_once(ROOT_PATH.'classes/dao/orders.class.php');

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['summary'] => '');

if($request->element('doo') == 'updateStatistic') {
	$yesterday = date("Y-m-d 00:00:00", time() - 86400);
	$session = date("Y-m-d h:i:s", time() - SESSION_TIME*60);
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

	$db->query("UPDATE `".DB_PREFIX."estore_statistics` SET tp='$tp',tpim='$tpim',oiy='$oiy',oim='$oim',riy='$riy',rim='$rim',te='$te',eim='$eim',last_updated='".date("Y-m-d H:i:s")."' WHERE id='$storeId'");
	
# Clean the online list
	$db->query("DELETE FROM `".DB_PREFIX."estore_online_users` WHERE `store_id`='$storeId' AND `last_updated` < '$session'");	
}

# Get latest orders
$orders = new Orders($storeId);
$latestOrders = $orders->getObjects(1,'1>0',array('created' => 'DESC'),10);
if($latestOrders) $template->assign('latestOrders',$latestOrders);

# Get top ordered products
$topOrderedProducts = array();
$last30days = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d"),date("Y"))-86400*300);
$results = $db->query("SELECT oi.product_id,p.sku,oi.name,AVG(oi.price) AS price,count(oi.product_id) AS total FROM `".DB_PREFIX."order_items` oi,`".DB_PREFIX."orders` o,`".DB_PREFIX."products` p WHERE p.id = oi.product_id AND o.store_id = '$storeId' AND oi.order_id=o.id and o.created >='$last30days' group by oi.product_id ORDER BY total DESC LIMIT 0,10");
if(mysql_num_rows($results)) {
	while($row=mysql_fetch_array($results)) {
		$topOrderedProducts[] = $row;
	}
}
$template->assign('topOrderedProducts',$topOrderedProducts);

# Get top viewed products
$topViewedProducts = array();
$results = $db->query("SELECT id,`name`,sku,price,viewed FROM `".DB_PREFIX."products` WHERE store_id = '$storeId' AND status=1 ORDER BY viewed DESC LIMIT 0,10");
if(mysql_num_rows($results)) {
	while($row=mysql_fetch_array($results)) {
		$topViewedProducts[] = $row;
	}
}
$template->assign('topViewedProducts',$topViewedProducts);

# Get number of online users
$onlineUsers = 0;
$results = $db->query("SELECT count(store_id) FROM `".DB_PREFIX."estore_online_users` WHERE store_id = '$storeId'");
if(mysql_num_rows($results)) {
	$row=mysql_fetch_row($results);
	$onlineUsers = $row[0];
}
$template->assign('onlineUsers',$onlineUsers);

# Get store statistic
$statistic = '';
$results = $db->query("SELECT * FROM `".DB_PREFIX."estore_statistics` WHERE id = '$storeId'");
if(mysql_num_rows($results)) {
	$row=mysql_fetch_array($results);
	$statistic = $row;
}
$template->assign('statistic',$statistic);
?>