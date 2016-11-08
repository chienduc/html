<?php
# Create Google Sitemap for Primevn
# Created by Mai Minh minh@maingo.com
# Date: 28/11/2006
#---------------------------------
# Autodetect current root folder
if (!defined( "ROOT_PATH" )) {
	define("ROOT_PATH", dirname(__FILE__)."/");
}

include_once(ROOT_PATH."includes/functions.inc.php");
include_once(ROOT_PATH."includes/config.inc.php");
include_once(ROOT_PATH."includes/constant.inc.php");
//include_once(ROOT_PATH."classes/data/textfilter.class.php");

$link = mysql_connect($config["db_server"],$config["db_user"],$config["db_pwd"]);
mysql_select_db($config["db_name"]) or die ("Cannot connect database!");

$tableprefix = 'dc_';

$url =DOMAIN;
$sitemap_file = "sitemap.xml";

# Priotity settings
$week_prio = 0.5;
$item_prio = 0.3;
$cat_prio = 0.8;

# Update frequency
$weekly = 'weekly';
$item_update = 'yearly';
$cat_update = 'daily';
$content = "";

# Print XML header
$content .= xml_head($url);

# Static pages
$result = mysql_query('SELECT `slug` FROM '.$tableprefix.'static WHERE status = 1');
while ($row = mysql_fetch_array($result))
{
	$static = $url."/vn/info/".$row['slug'].".html";
	$content .= print_xml($static,$cat_prio,date("Y-m-d"),$item_update);
}


$category = $url."/vn/news.html";
$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);


# Categories pages
$results = mysql_query('SELECT id,slug FROM ' . $tableprefix . 'article_categories WHERE status = 1');
while ($rows = mysql_fetch_array($results))
{
		$category = $url.'/vn/news'.$rows['slug'].'-'.'c'.$rows['id'].".html";
		$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);
		# News pages
        $parent =$rows['slug'];
	    $cid = $rows['id'];
		$news_result = mysql_query('SELECT * FROM '.$tableprefix.'articles WHERE status = 1 AND `cat_id` ='.$cid.'');
		while ($news_row = mysql_fetch_array($news_result))
			{ 
			$newdetail=$url."/vn/$parent/";
			$news = $newdetail.$news_row['slug'].'-'.$news_row['id'].".htm";
			$content .= print_xml($news,$item_prio,date("Y-m-d"),$weekly);
			}
}

$category = $url."/vn/fengshuis.html";
$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);

$results = mysql_query('SELECT id,slug FROM ' . $tableprefix . 'fengshui_categories WHERE status = 1');
while ($rows = mysql_fetch_array($results))
{
		$category = $url.'/vn/fengshuis/'.$rows['slug'].'-'.'c'.$rows['id'].".html";
		$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);
		# News pages
        $parent =$rows['slug'];
	    $cid = $rows['id'];
		$news_result = mysql_query('SELECT * FROM '.$tableprefix.'fengshuis WHERE status = 1 AND `cat_id` ='.$cid.'');
		while ($news_row = mysql_fetch_array($news_result))
			{ 
			$newdetail=$url."/vn/$parent/";
			$news = $newdetail.$news_row['slug'].'-'.$news_row['id'].".htm";
			$content .= print_xml($news,$item_prio,date("Y-m-d"),$weekly);
			}
}


$category = $url."/vn/experiences.html";
$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);

$results = mysql_query('SELECT id,slug FROM ' . $tableprefix . 'experience_categories WHERE status = 1');
while ($rows = mysql_fetch_array($results))
{
		$category = $url.'/vn/experiences/'.$rows['slug'].'-'.'c'.$rows['id'].".html";
		$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);
		# News pages
        $parent =$rows['slug'];
	    $cid = $rows['id'];
		$news_result = mysql_query('SELECT * FROM '.$tableprefix.'experiences WHERE status = 1 AND `cat_id` ='.$cid.'');
		while ($news_row = mysql_fetch_array($news_result))
			{ 
			$newdetail=$url."/vn/$parent/";
			$news = $newdetail.$news_row['slug'].'-'.$news_row['id'].".htm";
			$content .= print_xml($news,$item_prio,date("Y-m-d"),$weekly);
			}
}



# Product pages
$category = $url."/vn/projects.html";
$content .= print_xml($category,$cat_prio,date("Y-m-d"),$item_update);


$result = mysql_query('SELECT id,slug FROM ' . $tableprefix . 'product_categories WHERE status = 1 ');
while ($row = mysql_fetch_array($result))
{
	$category = $url."/vn/project/".$row['slug'].'-c'.$row['id'].".html";
	$content .= print_xml($category,$cat_prio,date("Y-m-d"),$item_update);
	# list product
	$parent =$row['slug'];
	 $cid = $row['id'];
	$pro_result = mysql_query('SELECT * FROM '.$tableprefix.'products WHERE status = 1 AND `cat_id` ='.$cid.'');
	while ($pro_row = mysql_fetch_array($pro_result))
			{
				$productdetail=$url."/vn/$parent"."/".$pro_row['slug'];
				$news = $productdetail.'-p'.$pro_row['id'].".html";
				$content .= print_xml($news,$item_prio,date("Y-m-d"),$item_update);
			}
		 
}

# Product pages
$category = $url."/vn/services.html";
$content .= print_xml($category,$cat_prio,date("Y-m-d"),$cat_update);


$result = mysql_query('SELECT id,slug FROM ' . $tableprefix . 'service_categories WHERE status = 1 ');
while ($row = mysql_fetch_array($result))
{
	$category = $url."/vn/services/".$row['slug'].'-c'.$row['id'].".html";
	$content .= print_xml($category,$cat_prio,date("Y-m-d"),$cat_update);
	# list product
	$parent =$row['slug'];
	 $cid = $row['id'];
	$pro_result = mysql_query('SELECT * FROM '.$tableprefix.'services WHERE status = 1 AND `cat_id` ='.$cid.'');
	while ($pro_row = mysql_fetch_array($pro_result))
			{
				$productdetail=$url."/vn/services/$parent"."/".$pro_row['slug'];
				$news = $productdetail.'-'.$pro_row['id'].".htm";
				$content .= print_xml($news,$item_prio,date("Y-m-d"),$cat_update);
			}
		
}

# Product pages
$category = $url."/vn/materials.html";
$content .= print_xml($category,$cat_prio,date("Y-m-d"),$cat_update);


$result = mysql_query('SELECT id,slug FROM ' . $tableprefix . 'material_categories WHERE status = 1 ');
while ($row = mysql_fetch_array($result))
{
	$category = $url."/vn/materials/".$row['slug'].'-c'.$row['id'].".html";
	$content .= print_xml($category,$cat_prio,date("Y-m-d"),$cat_update);
	# list product
	$parent =$row['slug'];
	 $cid = $row['id'];
	$pro_result = mysql_query('SELECT * FROM '.$tableprefix.'materials WHERE status = 1 AND `cat_id` ='.$cid.'');
	while ($pro_row = mysql_fetch_array($pro_result))
			{
				$productdetail=$url."/vn/materials/$parent"."/".$pro_row['slug'];
				$news = $productdetail.'-p'.$pro_row['id'].".html";
				$content .= print_xml($news,$item_prio,date("Y-m-d"),$cat_update);
			}
		
}

# Product pages
$category = $url."/vn/business.html";
$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);


$result = mysql_query('SELECT id,slug FROM ' . $tableprefix . 'business WHERE status = 1 ');
while ($row = mysql_fetch_array($result))
{
	$category = $url."/vn/business/".$row['slug'].'-c'.$row['id'].".html";
	$content .= print_xml($category,$cat_prio,date("Y-m-d"),$weekly);
	
		
}


$result = mysql_query('SELECT `slug`,`id` FROM '.$tableprefix.'question WHERE status = 1');
while ($row = mysql_fetch_array($result))
{
	$static = $url."/vn/question/".$row['slug']."-".$row['id'].".htm";
	$content .= print_xml($static,$cat_prio,date("Y-m-d"),$item_update);
}


$extend = $url."/vn/questions.html";
$content .= print_xml($extend,$cat_prio,date("Y-m-d"),$item_update);
 

# Print XML footer
$content .= xml_foot();
write_local_file($sitemap_file,$content);
echo "Success!";

function xml_head($url) {
	$freq = 'daily';
	$priority = '1.0';
	$mod = date("Y-m-d")."T".date("H:i:s")."+00:00";
	$str = "<?xml version='1.0' encoding='UTF-8'?>
<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\"
xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd\">
<url>
  <loc>$url</loc>
  <lastmod>$mod</lastmod>
  <changefreq>$freq</changefreq>
  <priority>$priority</priority>
</url>";
	return $str;
}
#-----------------------------------------------
# xml_foot
#-----------------------------------------------
function xml_foot() {
	$str = "
</urlset>";
	return $str;
}

#-----------------------------------------------
# print_xml
#-----------------------------------------------
function print_xml($url,$priority,$lastmod,$changefreq) {
	$str = "
<url>
  <loc>$url</loc>
  <priority>$priority</priority>
  <lastmod>$lastmod</lastmod>
  <changefreq>$changefreq</changefreq>
</url>";
	return $str;
}
?>
