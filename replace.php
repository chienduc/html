<?php
/*************************************************************************
Replace Tool
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
include_once(ROOT_PATH.'includes/functions.inc.php');

$folder = ROOT_PATH.'templates/dienthoai/';
 
$root = scandir($folder); 
foreach($root as $value) 
{ 
	if($value === '.' || $value === '..') {continue;}
	if(is_file($folder.$value)) {
		$filename = $folder.$value;
		$file = read_local_file($filename);
		$pattern = '/{\$messages\.([a-z0-9-_]*)}/';
		$replacement = '{$locale->msg(\'${1}\')}';
		$new = preg_replace($pattern, $replacement, $file);
		write_local_file($filename,$new,"w");	
	}
}
?>