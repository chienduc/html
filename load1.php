
<?php
die;
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


function get_data($url){
 $ch = curl_init();
 $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
 $data = curl_exec($ch);
  curl_close($ch);
 return $data;
}


	
	
//die;	
$catId=27;
$returned_content = get_data('http://www.sieuthison.vn/san-pham/vn_vi/danh-muc/203/1/1/Son-cho-go.html');
$output = trim(preg_replace('/\s\s+/', ' ',$returned_content));


preg_match_all('#<div[^>]*class=["\']col-san-pham cussp["\']*>(.*?)*<\/div>#is', $output, $list); 
$dir = 'upload/1/products/';

foreach($list[0] as $key=>$val){
	preg_match('#<img[^>]*class=["\']img-thumbnail cusimg["\'][^>]*src=["\'](.*?)["\']#is', $val, $images); 
	preg_match('#<b*>Giá: (.*?) vnđ*<\/b>#is', $val, $prices); 
	preg_match('#Mã SP: (.*?) *<\/p>#is', $val, $masp);
	preg_match('#href=["\'](.*?)["\']#is', $val, $link);
	preg_match('#title=["\'](.*?)["\']#is', $val, $title);
	


	
	$ima=$images[1];
	$nameima=str_replace(array("/","%20"),"-",strstr($ima,"sanpham/"));
	$file="http://www.sieuthison.vn".$ima;
	 $url = getimagesize($file);
    if (is_array($url)) {
	$imgurl = get_data($file);
    file_put_contents($dir.'a_'.$nameima, $imgurl);
	
	$file=str_replace("thumbs/","",$file);
	$imgurl = get_data($file);
    file_put_contents($dir.'l_'.$nameima, $imgurl);
	 }else $nameima='';
	
	
	
	 
	
	$link="http://www.sieuthison.vn".$link[1];
	
	$detail = get_data($link);
	$details = trim(preg_replace('/\s\s+/', ' ',$detail));
	preg_match('#data-fancybox-group=["\']button["\'][^>]*href=["\'](.*?)["\']#is', $detail, $bangmau);
	preg_match('#Trọng lượng: (.*?) *<\/b>#is', $detail, $trongluong);
	
	$ban=$bangmau[1];
	
	$nameban=str_replace(array("/","%20"),"-",strstr($ban,"bangmau/"));
	$file="http://www.sieuthison.vn".$ban;
    $url = getimagesize($file);
    if (is_array($url)) {
	$imgurl = get_data($file);
    file_put_contents($dir.'l_'.$nameban, $imgurl);
	
	$file=str_replace("upload/","upload/thumbs/",$file);
	$imgurl = get_data($file);
    file_put_contents($dir.'a_'.$nameban, $imgurl);
	
	}else{
		$nameban='';
	}
	
	#==================
	$acreages=$trongluong[1];
	$position=$key+1;
	$name=$title[1];
	$slug = TextFilter::urlize($name,false,'-');
	
	$price=str_replace(".","",$prices[1]);
	if($price<0) $price=0;
	$sku=$masp[1];
	$properties = array('avatar' => $nameima,
			            'color_board' => $nameban,
					    'photos' => '',
						'videos' => '',
						'files' => '',
						'user_upload' => '',
						'weight' =>'',
						'root_price' => '');
								
	
	$db->query("Insert into dc_products values ('','1', '".$catId."','0', '0','".$sku."','".$slug."','".$name."','','','','".$name."','".$name."','".$name."','','','".$price."','0','0','1000','".date("Y-m-d H:i:s")."','','".$position."','".serialize($properties)."','1','0','', '','','','','','".$acreages."','','','')");
	
	print_r($name."</br>");
	
	
}


echo  'done';
die;



?>