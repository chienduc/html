<?php
ob_start();

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
$templateFolder = 'standard/';
$userTemplate = 'standard';
$templateFile = 'index.tpl.html';

# HTTP Request manager
$request = new Request;
$op = $request->element('op'); 
$act = $request->element('act');

# Bootstrap
$bootstrap = Boot::checkBootstrap($sId);

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
if (in_array($op,$ops)) include_once(ROOT_PATH.'modules/'.strtolower($op).'/main.module.php');
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

# User log
$userId = 0;
$usertype = 0;
$username = 'Guest';
if(isset($_SESSION['userId']) && $_SESSION['userId'] && $userInfo) {$userId = $_SESSION['userId']; $username = $userInfo->getUsername(); $usertype = $userInfo->getType();}
if(isset($_SESSION['store_customerId']) && $_SESSION['store_customerId'] && $customerInfo) {$userId = $_SESSION['store_customerId']; $usertype = 4; $username = $customerInfo->getUsername();}

if($act != "logout"){
    userLog($storeId,$userId,$username,$usertype,$_SERVER['REQUEST_URI']);
  
 
} 

$filetc="truycap.txt";
$totaltc = file_get_contents('truycap.txt'); 
if(isset($_SESSION['CHECK_ACCESS']) && $_SESSION['CHECK_ACCESS'] != $_SERVER['REMOTE_ADDR']){
 if($totaltc>0){
    $updatetc = fopen("$filetc", "w+");
    fwrite($updatetc,$totaltc+10); 
 }
}else $_SESSION['CHECK_ACCESS']=$_SERVER['REMOTE_ADDR'];
 

$template->assign('NumTotalUserAccess',$totaltc);

# Display the web page
$template->template_dir = $template_dir;
$template->display($templateFile);
# Close database connection
$db->close();
$time_end = microtime(true);
$time = $time_end - $time_start;

?>