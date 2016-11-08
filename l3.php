
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
$catId=51;
$returned_content = get_data('http://sonnuocmiennam.com/san-pham/son-expo/son-nuoc-ngoai-that-expo/');
$output = trim(preg_replace('/\s\s+/', ' ',$returned_content));


preg_match('#<ul[^>]*class=["\']products["\']*>(.*?)*<\/ul>#is', $output, $list); 

preg_match_all('#<li[^>]*>(.*?)*<\/li>#is', $list[0], $list); 
$dir = 'upload/1/products/';



foreach($list[0] as $key=>$val){
	preg_match('#src=["\'](.*?)["\']#is', $val, $images); 
	preg_match('#<span[^>]class=["\']amount["\']*>(.*?)<\/span>#is', $val, $prices); 
	//preg_match('#Mã SP: (.*?) *<\/p>#is', $val, $masp);
	preg_match('#href=["\'](.*?)["\']#is', $val, $link);
	preg_match('#<h3>(.*?)</h3>#is', $val, $title);


    //
	$name=$title[1];
	$slug = TextFilter::urlize($name,false,'-');
	



	
	$ima=$images[1];
	$nameima=$slug.substr($ima,strlen($ima)-4,4);

	$file=$ima;
	 $url = getimagesize($file);
    if (is_array($url)) {
	$imgurl = get_data($file);
    file_put_contents($dir.'a_'.$nameima, $imgurl);
	
	//$file=str_replace("thumbs/","",$file);
	//$imgurl = get_data($file);
    //file_put_contents($dir.'l_'.$nameima, $imgurl);
	 }else $nameima='';
	
	
	
	 
	
	$link=$link[1];
	
	$detail = get_data($link);
	$details = trim(preg_replace('/\s\s+/', ' ',$detail));
	
    preg_match('#<div[^>]class=["\']thumbnails[^>]columns-3["\'](.*?)<\/div>#is', $details, $bangmaus);
	
	preg_match('#<div[^>]class=["\']images["\'](.*?)<\/div>#is', $details, $hinhfull);
	

	preg_match('#href=["\'](.*?)["\']#is', $bangmaus[0], $bangmau);
	preg_match('#src=["\'](.*?)["\']#is', $bangmaus[0], $abangmau);
	preg_match('#href=["\'](.*?)["\']#is', $hinhfull[0], $hinhfull);
	
	$imgurls = get_data($hinhfull[1]);
    file_put_contents($dir.'l_'.$nameima, $imgurls);
	
	
	preg_match('#<title*>(.*?)<\/title>#is', $detail, $keywords);
	preg_match('#<meta[^>]property=["\']og:description["\'][^>]content=["\'](.*?)["\']#is', $detail, $description);

	
	// noi dung
	preg_match('#<div[^>]*class=["\']panel[^>]entry-content[^>]wc-tab["\'][^>]*id=["\']tab-description["\'][^>]*>(.*?)<\/div>#is', $detail, $noidung);

	
	
	
	$ban=$bangmau[1];
	$aban=$abangmau[1];
	
	$nameban='bang-mau-'.$slug.substr($ban,strlen($ban)-4,4);
	$file=$ban;
    $url = getimagesize($file);
    if (is_array($url)) {
	$imgurl = get_data($file);
    file_put_contents($dir.'l_'.$nameban, $imgurl);
	
	$file=$aban;
	$imgurl = get_data($file);
    file_put_contents($dir.'a_'.$nameban, $imgurl);
	
	}else{
		$nameban='';
	}
	
	#==================
	$acreages='';
	$keywords='dailyson247 nha phan phoi son toan quoc, dai ly son, đại lý sơn giá rẽ, sơn uy tín'.$keywords[1];
	$description='Dailyson247 nhà phân phối sơn toàn quốc, là đại lý phân phối, cung cấp, bán sơn giá rẽ, chính hãng, úy tính, chất lượng.'.$description[1];
	$noidung=$noidung[0];
	$position=$key+1;
	
	
	$price=str_replace(".","",$prices[1]);
	if($price<0) $price=0;
	$sku=$slug = TextFilter::urlize(strtoupper($name),false,'_');
	$properties = array('avatar' => $nameima,
			            'color_board' => $nameban,
					    'photos' => '',
						'videos' => '',
						'files' => '',
						'user_upload' => '',
						'weight' =>'',
						'root_price' => '');
								
	
	$db->query("Insert into dc_products values ('','1', '".$catId."','0', '0','".$sku."','".$slug."','".$name."','','','','".$keywords."','".$description."','".stripslashes($noidung)."','','','0','0','0','1000','".date("Y-m-d H:i:s")."','','".$position."','".serialize($properties)."','1','0','', '','','','','','".$acreages."','','','')");
	
	print_r($name."</br>");
	
	
}


echo  'done';
die;



?>