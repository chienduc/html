<?php
/*************************************************************************
Index page
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 30/05/2012
Coder: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
$time_start = microtime(true);
error_reporting(9);
if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', dirname(__FILE__).'/');
}
include_once(ROOT_PATH.'includes/config.inc.php');
include_once(ROOT_PATH.'includes/constant.inc.php');
include_once(ROOT_PATH.'classes/data/translator.class.php');
include_once(ROOT_PATH.'includes/functions.inc.php');
include_once(ROOT_PATH.'classes/database/mysql.class.php');
include_once(ROOT_PATH.'classes/security/boot.class.php');
include_once(ROOT_PATH.'classes/template/smarty.class.php');
include_once(ROOT_PATH.'classes/http/request.class.php');
include_once(ROOT_PATH.'classes/http/url.class.php');
include_once(ROOT_PATH.'classes/dao/users.class.php');
include_once(ROOT_PATH.'classes/dao/estores.class.php');
include_once(ROOT_PATH.'classes/dao/customers.class.php');
include_once(ROOT_PATH.'classes/dao/carts.class.php');
include_once(ROOT_PATH.'classes/dao/languages.class.php');
include_once(ROOT_PATH.'classes/dao/currencies.class.php');
include_once(ROOT_PATH.'classes/dao/addons.class.php');

# Setting time zone
if(function_exists('date_default_timezone_set')) date_default_timezone_set(TIME_ZONE);

# Database connection
$db = new DB();

# Template engine
$template_dir = array(ROOT_PATH.TEMPLATE_PATH.'/default/');
$template = new Smarty;
$template->compile_check = TEMPLATE_COMPILE;
$template->debugging = TEMPLATE_DEBUG;

# Initialize some variables
$sort_key = '';
$sort_direction = '';
$pageTitle = '';
$pageKeywords = '';
$pageDescription = '';
		
# E-store configuration
$templateFolder = 'bees/';
$userTemplate = 'bees/';
$templateFile = 'change.html';

# HTTP Request manager
$request = new Request;
$op = $request->element('op'); 
$act = $request->element('act');

# Bootstrap
$bootstrap = Boot::checkBootstrap(&$sId);

# Checking if this is an e-store.
if($sId) $op = 'estore';
if(!$op) $op = DEFAULT_OP;
if(!$act) $act = DEFAULT_ACT;

$addons = new Addons($sId);
# Put this code any where in the modules
#foreach($addons->getAddonFromEvent('ORDER_NEW') as $addon) {include_once(ROOT_PATH."addons/$addon/addon.php");}

# Session manager
include_once(ROOT_PATH.'includes/session.inc.php');
# Include action module
//if (in_array($op,$ops)) include_once(ROOT_PATH.'modules/'.strtolower($op).'/main.module.php');
$full_header = 1;
# Some global variables
$template->assign('templatePath',TEMPLATE_PATH);
$template->assign('domain',DOMAIN);
$template->assign('op',$op);
$template->assign('act',$act);
if($sort_key) $template->assign('sk',$sort_key);
if($sort_direction) $template->assign('sd',$sort_direction);
$template->assign('pageTitle',$pageTitle);
$template->assign('pageKeywords',$pageKeywords);
$template->assign('pageDescription',$pageDescription);
$template->assign('full_header',$full_header);
if(isset($storeId)) $template->assign('storeId',$storeId);

include_once(ROOT_PATH.'classes/dao/articles.class.php');
$articles= new Articles(1);
$listNew = $articles->getObjects(1,"status=1",array("date_created"=>"DESC"),6);
/*echo "<pre>";
print_r($listNew);
echo "</pre>";*/
foreach($listNew as $new){
}
$template->assign("Title",$listNew);
# Display the web page
$template->template_dir = $template_dir;
$template->display($templateFile);
# Close database connection
$db->close();
/*$sql = "SELECT * FROM `n_news` WHERE id=460";
$results = $db->query($sql);
while ($row = mysql_fetch_array($results)){
$row['title'] = $row['title'];
echo "<pre>";
print_r($row);
echo "</pre>";
	$avatar = $row['avatar'];
	$properties = array();
	$properties['avatar'] = $avatar;
	//$sql2 = "UPDATE `cms_bees`.`dc_articles` SET `properties` = '".serialize($properties)."' WHERE `id`='".$row['id']."'";
	//$sql2 = "INSERT INTO `cms_bees`.`dc_articles` (`id`, `store_id`, `cat_id`, `slug`, `title`, `keyword`, `sapo`, `detail`, `viewed`, `date_created`, `date_update`, `status`, `properties`, `position`, `home`) VALUES ('".$row['id']."', '1', '".$row['cid']."', '".$row['slug']."', '".$row['title']."', '".$row['keywords']."', '".$row['sapo']."', '".$row['details']."', '', '".$row['date_created']."', '', '".$row['status']."', '".unserialize($properties)."', '".$row['position']."', '".$row['home']."')";
	//$result = $db->query($sql2);
	//if($result) echo 'ok';
}
*/
/*$sql = "SELECT * FROM `n_categories` WHERE 1 = 1";
$results = $db->query($sql);
while ($row = mysql_fetch_array($results)){
echo "<pre>";
print_r($row);
echo "</pre>";
	$sql2 = "INSERT INTO `cms_bees`.`dc_article_categories` (`id`, `parent_id`, `store_id`, `slug`, `name`, `keyword`, `sapo`, `position`, `num_article`, `viewed`, `properties`, `status`, `home`) VALUES ('".$row['id']."', '".$row['pid']."', '1', '".$row['slug']."', '".$row['name']."', '', '".$row['details']."', '".$row['position']."', '', '', '', '".$row['status']."', '".$row['home']."')";
	$result = $db->query($sql2);
	if($result) echo 'ok';
}*/
?>