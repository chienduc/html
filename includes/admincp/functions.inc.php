<?php
/*************************************************************************
Admin Functions
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 15/07/2008
Author: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
function checkPermission($need=array('7','8','9')) {
	global $userInfo;
	$level = $userInfo->getType();
	if(is_array($need)) {
		if(!in_array($level,$need)) {
			header("location: /".ADMINCP_SCRIPT."?op=accessdenied");
			exit;
		}
	} else {
		if($level != $need) {
			header("location: /".ADMINCP_SCRIPT."?op=accessdenied");
			exit;		
		}
	}
}


# Not used maiminh 01/08/2011
function cleanHTML($str) {
	return stripslashes($str);
}

function createComboFromSql($sql,$db,$value=''){		
	$result = $db->query($sql);		
	$str = '';
	while($row = mysql_fetch_array($result)){
		$str .= "<option value=\"".$row['id']."\"".($row['id']==$value?" selected":"").">".$row['name']."</option>";
	}				
	mysql_free_result($result);
	return $str;
}	
function createJumpComboFromSql($sql,$db,$value='',$query=''){
	$result = $db->query($sql);		
	$str = '';
	while($row = mysql_fetch_array($result)){
		$url = $query.$row['id'];	
		$str .= "<option value=\"$url\"".($row['id']==$value?" selected":"").">".$row['name']."</option>";
	}				
	mysql_free_result($result);
	return $str;
}				
function optionRestaurantType($value='',$lang='vn',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,".$lang."_name AS name FROM ".DB_PREFIX."restaurant_types WHERE active=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionProvince($value='',$lang='vn',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,".$lang."_name AS name FROM ".DB_PREFIX."areas WHERE parent_id<>1 AND active=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionTemplate($userId,$value='',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,name FROM ".DB_PREFIX."templates WHERE status=1 AND (type=1 OR (type=2 AND owner_id='".$userId."')) AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionCapacity($value='',$lang='vn',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,".$lang."_details AS name FROM ".DB_PREFIX."capacity WHERE active=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionStatus($value='',$lang='vn',$where='(1=1)'){
	$str = '';
	$str = '<option value="0"'.($value==0?" selected":"").'>Vô hiệu</option>
	<option value="1"'.($value==1?" selected":"").'>Kích hoạt</option>
	<option value="2"'.($value==2?" selected":"").'>Đã bị xóa</option>';
	return $str;
}
function optionPager($url,$pages=1,$page=1){
	$str = '';
	for($i=1;$i<=$pages;$i++) {
		$str .= '<option value="'.$url.$i.'"'.($page==$i?' selected':'').'>'.$i.'</option>';
	}
	return $str;
}
function optionUserType($value='',$lang='vn',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,".$lang."_type AS name FROM ".DB_PREFIX."user_types WHERE status=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionNoRestaurantUsersList($value=''){
	global $db;
	$sql = "SELECT id,username as name FROM ".DB_PREFIX."users WHERE type IN (0,1) AND status=1 AND id NOT IN (SELECT memberid FROM ".DB_PREFIX."user_restaurants) ORDER BY name";
	return createComboFromSql($sql,$db,$value);
}
function linkPager($url,$pages=1,$page=1,$separator='|'){
	$str = '';
	$start=$page-3;
	if($start<1) $start=1;
	$end=$page+3;
	if($end>$pages) $end=$pages;
	for($i=$start;$i<=$end;$i++) {
		if($i==$page) {
			$str .= '&nbsp;<strong>'.$i.'</strong>&nbsp;'.$separator;
		} else {
			$str .= '&nbsp;<a href="'.$url.$i.'">'.$i.'</a>&nbsp;'.$separator;
		}
	}
	return $str;
}
function optionRowsPerPage($value=10){
	$str = '';
	$str = '<option value="5"'.($value==5?" selected":"").'>5</option>
	<option value="10"'.($value==10?" selected":"").'>10</option>
	<option value="15"'.($value==15?" selected":"").'>15</option>
	<option value="20"'.($value==20?" selected":"").'>20</option>
	<option value="25"'.($value==25?" selected":"").'>25</option>
	<option value="50"'.($value==50?" selected":"").'>50</option>
	<option value="100"'.($value==100?" selected":"").'>100</option>
	<option value="200"'.($value==200?" selected":"").'>200</option>
	<option value="500"'.($value==500?" selected":"").'>500</option>';
	return $str;
}
function optionItemsPerRow($value=3){
	$str = '';
	$str = '<option value="1"'.($value==1?" selected":"").'>1</option>
	<option value="2"'.($value==2?" selected":"").'>2</option>
	<option value="3"'.($value==3?" selected":"").'>3</option>
	<option value="4"'.($value==4?" selected":"").'>4</option>
	<option value="5"'.($value==5?" selected":"").'>5</option>';
	return $str;
}
function optionPhotoRowsPerPage($value=3){
	$str = '';
	$str = '<option value="1"'.($value==1?" selected":"").'>1</option>
	<option value="2"'.($value==2?" selected":"").'>2</option>
	<option value="3"'.($value==3?" selected":"").'>3</option>
	<option value="4"'.($value==4?" selected":"").'>4</option>
	<option value="5"'.($value==5?" selected":"").'>5</option>
	<option value="6"'.($value==6?" selected":"").'>6</option>
	<option value="7"'.($value==7?" selected":"").'>7</option>
	<option value="8"'.($value==8?" selected":"").'>8</option>
	<option value="9"'.($value==9?" selected":"").'>9</option>
	<option value="10"'.($value==10?" selected":"").'>10</option>';
	return $str;
}
function optionLanguages($value='vn'){
	$str = '';
	$str = '<option value="vn"'.($value=='vn'?" selected":"").'>Tiếng Việt</option>
	<option value="en"'.($value=='en'?" selected":"").'>Tiếng Anh</option>';
#	<option value="jp"'.($value=='jp'?" selected":"").'>Tiếng Nhật</option>
#	<option value="cn"'.($value=='cn'?" selected":"").'>Tiếng Hoa</option>';
	return $str;
}
function optionAlbums($value=0, $status='') {
# Khong chay de quy ma chi gioi han 2 level thoi.
	global $db;
	$str_status = $status!=''?" AND status='$status'":"";
	$str = '<option value="0"'.($value==0?" selected":"").'>/&nbsp;&nbsp;&nbsp;</a>';
	$sql = "SELECT * FROM ".DB_PREFIX."album WHERE pid='0'".$str_status;
	$result = $db->query($sql);
	if(mysql_num_rows($result)) {
		while($row=mysql_fetch_array($result)) {
			$str .= "<option value=\"".$row['id']."\"".($value==$row['id']?" selected":"").">".$row['vn_name']."</option>";
			$cresult = $db->query("SELECT * FROM ".DB_PREFIX."album WHERE pid='".$row['id']."'".$str_status);
			if(mysql_num_rows($cresult)) {
				while($crow = mysql_fetch_array($cresult)) {
					$str .= "<option value=\"".$crow['id']."\"".($value==$crow['id']?" selected":"").">&nbsp;&nbsp;&nbsp;".$crow['vn_name']."</option>";				
					$cresult2 = $db->query("SELECT * FROM ".DB_PREFIX."album WHERE pid='".$crow['id']."'".$str_status);					
					if(mysql_num_rows($cresult2)) {
						while($crow2 = mysql_fetch_array($cresult2)) {
							$str .= "<option value=\"".$crow2['id']."\"".($value==$crow2['id']?" selected":"").">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$crow2['vn_name']."</option>";				
						}
					}
				}
				mysql_free_result($cresult);					
			}
		}
	}
	mysql_free_result($result);
	return $str;
}
//Function to remove HTML tags, Javascript,...
function Filter($sstring){
$search = array ("'<'",  // Strip out javascript
				 "'>'",
				 "'\"'",
				 "'\''",
                 "'[\/\!]*?[^<>]*?>'si",  // Strip out html tags
                 "'([\r\n])[\s]+'",  // Strip out white space
                 "'&(quot|#34);'i",  // Replace html entities
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&(gt|#62);'i",
                 "'&(nbsp|#160);'i",
                 "'&(iexcl|#161);'i",
                 "'&(cent|#162);'i",
                 "'&(pound|#163);'i",
                 "'&(copy|#169);'i",
                 "'&#(\d+);'e");  // evaluate as php
$replace = array ("",
				  "",
				  "",
				  "",
				  "",
				  "",
                  "",
                  "",
                  "",
                  "",
                  "",
                  "",
                  "",
                  "",
                  "",
                  "");
$text = preg_replace ($search, $replace, $sstring);
$data = explode("\\",$text);
$cleaned = implode("",$data);
return $cleaned;
}

# Created by maiminh 10/05/2005
function resize($image_path,$thumb_path,$image_name,$thumb_name,$dimension,$square=0,$quality=90) 
{ 
	$type = strtolower(substr($image_name,-3));
	switch ($type) {
		case 'jpg':
		    $src = imagecreatefromjpeg("$image_path/$image_name"); 	
			break;
		case 'gif':
		    $src = imagecreatefromgif("$image_path/$image_name"); 	
			break;
		case 'png':
		    $src = imagecreatefrompng("$image_path/$image_name"); 	
			break;
		case 'bmp':
		    $src = imagecreatefrombmp("$image_path/$image_name"); 	
			break;
	}
    $ow=imagesx($src);
    $oh=imagesy($src);
	$src_x = 0;
	$src_y = 0;
	if($ow>$oh) {
		if($ow>$dimension) {
			$nw = $dimension;
			$nh = (int)$oh*$nw/$ow;
		} else {
			$nw = $ow;
			$nh = (int)$oh*$nw/$ow;
		}
	} else {
		if($oh>$dimension) {
			$nh = $dimension;
			$nw = (int)$ow*$nh/$oh;
		} else {
			$nh = $oh;
			$nw = (int)$ow*$nh/$oh;
		}
	}
	if($square) {
		$length = min($ow,$oh);
		$src_x = ceil( $ow / 2 ) - ceil( $length / 2 );
		$src_y = ceil( $oh / 2 ) - ceil( $length / 2 );
		$nlength = max($nw,$nh);
		$nw = $nlength;
		$nh = $nlength;
		$ow = $length;
		$oh = $length;
	}
	$dst = imagecreatetruecolor($nw,$nh);
    imagecopyresampled($dst,$src,0,0,$src_x,$src_y,$nw,$nh,$ow,$oh); 
	imagejpeg($dst, "$thumb_path/$thumb_name",$quality);
	imagedestroy($src);
	imagedestroy($dst);	
    return true; 
} 

# Created by maiminh 10/01/2007
function watermark($image_path,$logo_path, $quality=90) {
	$resultImage = imagecreatefromjpeg($image_path);
	imagealphablending($resultImage, TRUE);
	
	$finalWaterMarkImage = imagecreatefrompng($logo_path);
	$finalWaterMarkWidth = imagesx($finalWaterMarkImage);
	$finalWaterMarkHeight = imagesy($finalWaterMarkImage);
	
	imagecopy($resultImage,$finalWaterMarkImage,0,0,0,0,$finalWaterMarkWidth,$finalWaterMarkHeight);
	
	imagejpeg($resultImage,$image_path,$quality); 
	
	imagedestroy($resultImage);
	imagedestroy($finalWaterMarkImage);
}
function ConvertBMP2GD($src, $dest = false) {
	if(!($src_f = fopen($src, "rb"))) {
		return false;
	}
	if(!($dest_f = fopen($dest, "wb"))) {
		return false;
	}
	$header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f,14));
	$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant",
	fread($src_f, 40));
	extract($info);
	extract($header);
	if($type != 0x4D42) { // signature "BM"
		return false;
	}
	$palette_size = $offset - 54;
	$ncolor = $palette_size / 4;
	$gd_header = "";
	// true-color vs. palette
	$gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
	$gd_header .= pack("n2", $width, $height);
	$gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
	if($palette_size) {
		$gd_header .= pack("n", $ncolor);
	}
	// no transparency
	$gd_header .= "\xFF\xFF\xFF\xFF";
	fwrite($dest_f, $gd_header);
	if($palette_size) {
		$palette = fread($src_f, $palette_size);
		$gd_palette = "";
		$j = 0;
		while($j < $palette_size) {
			$b = $palette{$j++};
			$g = $palette{$j++};
			$r = $palette{$j++};
			$a = $palette{$j++};
			$gd_palette .= "$r$g$b$a";
		}
		$gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
		fwrite($dest_f, $gd_palette);
	}
	$scan_line_size = (($bits * $width) + 7) >> 3;
	$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;
	for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
		// BMP stores scan lines starting from bottom
		fseek($src_f, $offset + (($scan_line_size + $scan_line_align) *	$l));
		$scan_line = fread($src_f, $scan_line_size);
		if($bits == 24) {
			$gd_scan_line = "";
			$j = 0;
			while($j < $scan_line_size) {
				$b = $scan_line{$j++};
				$g = $scan_line{$j++};
				$r = $scan_line{$j++};
				$gd_scan_line .= "\x00$r$g$b";
			}
		}
		else if($bits == 8) {
			$gd_scan_line = $scan_line;
		}
		else if($bits == 4) {
			$gd_scan_line = "";
			$j = 0;
			while($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = chr($byte >> 4);
				$p2 = chr($byte & 0x0F);
				$gd_scan_line .= "$p1$p2";
			}
			$gd_scan_line = substr($gd_scan_line, 0, $width);
		}
		else if($bits == 1) {
			$gd_scan_line = "";
			$j = 0;
			while($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = chr((int) (($byte & 0x80) != 0));
				$p2 = chr((int) (($byte & 0x40) != 0));
				$p3 = chr((int) (($byte & 0x20) != 0));
				$p4 = chr((int) (($byte & 0x10) != 0));
				$p5 = chr((int) (($byte & 0x08) != 0));
				$p6 = chr((int) (($byte & 0x04) != 0));
				$p7 = chr((int) (($byte & 0x02) != 0));
				$p8 = chr((int) (($byte & 0x01) != 0));
				$gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
			}
			$gd_scan_line = substr($gd_scan_line, 0, $width);
		}
		fwrite($dest_f, $gd_scan_line);
	}
	fclose($src_f);
	fclose($dest_f);
	return true;
}
function imagecreatefrombmp($filename) {
	$tmp_name = tempnam("/tmp", "GD");
	if(ConvertBMP2GD($filename, $tmp_name)) {
		$img = imagecreatefromgd($tmp_name);
		unlink($tmp_name);
		return $img;
	}
	return false;
}
function convertDateTime($datetime) {
	$split = split(" ",$datetime);
	$date = $split[0];
	$time = $split[1];
	$date = split("/",$date);
	return $date[2]."-".$date[1]."-".$date[0]." ".$time;
}
function optionGroup($value='',$where='(1=1)'){
	$str = '';
	$str = '<option value="1"'.($value==1?" selected":"").'>1</option>
	<option value="2"'.($value==2?" selected":"").'>2</option>
	<option value="3"'.($value==3?" selected":"").'>3</option>
	<option value="4"'.($value==4?" selected":"").'>4</option>
	<option value="5"'.($value==5?" selected":"").'>5</option>';
	return $str;
}
function optionKey($value='') {
	$str = '<option value="0"'.($value==0?" selected":"").'>#</option>';
	for($i=97;$i<=122;$i++) {
		$str .= "<option value=\"".chr($i)."\"".($value==chr($i)?" selected":"").">".chr($i-32)."</option>";
	}
	return $str;
}
function optionFaqCategories($value='',$lang='vn',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,".$lang."_name AS `name` FROM ".DB_PREFIX."faq_categories WHERE status=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionAdGroups($value='',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,`name` FROM ".DB_PREFIX."ad_groups WHERE status=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionBranches($value='',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,`name` as `name` FROM ".DB_PREFIX."branch WHERE 1=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function jumpBranches($value='',$where='(1=1)',$query="&bId="){
	global $db;
	$sql = "SELECT id,`name` as `name` FROM ".DB_PREFIX."branch WHERE 1=1 AND ($where)";
	return createJumpComboFromSql($sql,$db,$value,$query);
}
function optionMenuGroups($value='',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,`vn_name` as `name` FROM ".DB_PREFIX."menu_groups WHERE 1=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionCategories($value='',$where='(1=1)'){
	global $db;
	$sql = "SELECT id,`name` as `name` FROM ".DB_PREFIX."categories WHERE 1=1 AND ($where)";
	return createComboFromSql($sql,$db,$value);
}
function optionAllCategories($value=''){
	global $db;
	$str = '';
	$sql = "SELECT id,`name` as `name` FROM ".DB_PREFIX."categories WHERE pid=0 ORDER BY position";
	$result = $db->query($sql);
	if(mysql_num_rows($result)) {
		while($row = mysql_fetch_array($result)) {
			$str .= "<option value='".$row['id']."'".($value==$row['id']?" selected":"").">".$row['name']."</option>";
			$sql = "SELECT id,`name` as `name` FROM ".DB_PREFIX."categories WHERE pid='".$row['id']."'";
			$rs = $db->query($sql);
			if(mysql_num_rows($rs)) {
				while($rows = mysql_fetch_array($rs)) {
					$str .= "<option value='".$rows['id']."'".($value==$rows['id']?" selected":"").">&nbsp;&nbsp;&nbsp;|--".$rows['name']."</option>";
				}
				mysql_free_result($rs);
			}
		}
		mysql_free_result($result);
	}
	return $str;
}
function isJpeg($str) {
	$ext = strtolower(substr($str,-3));
	if(preg_match("/jpg/",$ext)) return 1;
	return 0;
}
function isImage($str) {
	$ext = strtolower(substr($str,-3));
	if(preg_match("/jpg|png|bmp|gif/",$ext)) return 1;
	return 0;
}
function isVideo($str) {
	$ext = strtolower(substr($str,-3));
	if(preg_match("/wmv|mpg|mp4|mov|avi|flv/",$ext)) return 1;
	return 0;
}
function isFlash($str) {
	$ext = strtolower(substr($str,-3));
	if(preg_match("/swf/",$ext)) return 1;
	return 0;
}
function isMusic($str) {
	$ext = strtolower(substr($str,-3));
	if(preg_match("/wma|wav|mp3|asf|/",$ext)) return 1;
	return 0;
}

function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
}
?>