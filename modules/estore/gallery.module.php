<?php
/*************************************************************************
Module gallery
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
**************************************************************************/
$templateFile="gallery.tpl.html";
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['photo_gallery'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
$pageTitle = $messages['photo_gallery'];
$gallery_path = "upload/files/gallery/";
$template->assign("gallery_path",$gallery_path);
$album1 = $gallery_path."album_1/";
$album2 = $gallery_path."album_2/";
$album3 = $gallery_path."album_3/";
$album4 = $gallery_path."album_4/";
$album5 = $gallery_path."album_5/";

// check gallery 
if(is_dir($gallery_path)){
	 $folder = array();
	 $open_dir_album = opendir($gallery_path);
	 $i = 0;
	 while (($fileAlbum = readdir($open_dir_album)) !== false) {
	 	$i = $i + 1;
		if($fileAlbum != '.' && $fileAlbum != '..') {  
			if(eregi("\.jpg|\.gif|\.png",$fileAlbum)){
			 }else{
			 	$nameforder = "album".$i;
				$nameforder = $gallery_path.$fileAlbum."/";
				$folder[] = $fileAlbum;
				// check images
				if (is_dir($nameforder) ) {
					$photos1=array();
					$open_dir = opendir($nameforder);
					#
					while (($file = readdir($open_dir)) !== false) {
						if($file != '.' && $file != '..') {  
						if(eregi("\.jpg|\.gif|\.png",$file)){
						   $photos[]=$file;
						}
						}
				   }
				$template->assign("photos1",$photos1);
					closedir($open_dir);
				} 
			 }
		}
	 } // end while album
	 $template->assign("folders",$folder);
}// end gallery path


if (is_dir($album1) ) {
		$photos1=array();
        $open_dir = opendir($album1);
        while (($file = readdir($open_dir)) !== false) {
            if($file != '.' && $file != '..') {  
			if(eregi("\.jpg|\.gif|\.png",$file)){
			   $photos1[]=$file;
			}
			}
	   }
    $template->assign("photos1",$photos1);
	    closedir($open_dir);
    } 
if (is_dir($album2) ) {
		$photos2=array();
        $open_dir = opendir($album2);
        while (($file = readdir($open_dir)) !== false) {
            if($file != '.' && $file != '..') {  
			if(eregi("\.jpg|\.gif|\.png",$file)){
			   $photos2[]=$file;
			}
			}
	   }
    $template->assign("photos2",$photos2);
	    closedir($open_dir);
    } 
if (is_dir($album3) ) {
		$photos3=array();
        $open_dir = opendir($album3);
        while (($file = readdir($open_dir)) !== false) {
            if($file != '.' && $file != '..') {  
			if(eregi("\.jpg|\.gif|\.png",$file)){
			   $photos3[]=$file;
			}
			}
	   }
    $template->assign("photos3",$photos3);
	    closedir($open_dir);
    } 
if (is_dir($album4) ) {
		$photos4=array();
        $open_dir = opendir($album4);
        while (($file = readdir($open_dir)) !== false) {
            if($file != '.' && $file != '..') {  
			if(eregi("\.jpg|\.gif|\.png",$file)){
			   $photos4[]=$file;
			}
			}
	   }
    $template->assign("photos4",$photos4);
	    closedir($open_dir);
    } 
if (is_dir($album5) ) {
		$photos5=array();
        $open_dir = opendir($album5);
        while (($file = readdir($open_dir)) !== false) {
            if($file != '.' && $file != '..') {  
			if(eregi("\.jpg|\.gif|\.png",$file)){
			   $photos5[]=$file;
			}
			}
	   }
    $template->assign("photos5",$photos5);
	    closedir($open_dir);
    } 