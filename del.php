
<?php
//die;
error_reporting(9);
if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', dirname(__FILE__).'/');
}

header('Content-Type: text/html; charset=utf-8');


include_once(ROOT_PATH.'includes/config.inc.php');
include_once(ROOT_PATH.'includes/constant.inc.php');
include_once(ROOT_PATH.'includes/functions.inc.php');
include_once(ROOT_PATH.'classes/database/mysql.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");

# Database connection
$db = new DB();


$sql = $db->query("select * from dc_products where cat_id not in (1,2,3,4,5,6,7,8,9,10,11,12,13,28,29)");

 while($row = $db->fetchArray($sql)){
	$id=$row['id'];
	
	$properties=unserialize($row['properties']);
	$avatar=$properties['avatar'];
	$bangmau=$properties['color_board'];
	//unlink("/home/dailyson/public_html/upload/1/products/l_".$avatar);
	//unlink("/home/dailyson/public_html/upload/1/products/a_".$avatar);
	//unlink("/home/dailyson/public_html/upload/1/products/l_".$bangmau);
	//unlink("/home/dailyson/public_html/upload/1/products/a_".$bangmau);
   //$sqls = "DELETE FROM dc_products WHERE id = '$id'";
   //$db->query($sqls);
	print_r($id);print_r('<br>');
	
	
	
	        
		// Isset From ->> To
}
echo  'done';
die;



?>