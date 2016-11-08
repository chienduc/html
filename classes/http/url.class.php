<?php
/*************************************************************************
Class URL
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 01/06/2012
**************************************************************************/
class Url
{
	function Url() {
	}

	# Generate the URL of a page
	function genUrl($content = '', $path = '') {
		$url = '';
		if (URL_TYPE == 1)		# Query string
			$url = '/'.SCRIPT."?&content=$content";
		elseif(URL_TYPE == 2)	# SEO
			$url = '/'.($path?"$path/":'')."$content.html";
		return $url;
	}
	
	# Generate the page navigation bar
	function genPager($url, $pages = 1, $page = 1, $bound = 5,$img_path='/images/'){
		$pager = array();
		$start = $page - $bound;
		if($start < 1) $start = 1;
		$end = $page + $bound;
		if($end > $pages) $end = $pages;
		$pager[] = array('name' => '<img src="'.$img_path.'ico_first.png" alt="first" >','url' => sprintf($url,1), 'current' => 0);
		$pager[] = array('name' => '<img src="'.$img_path.'ico_prev.png" alt="previous">','url' => sprintf($url,$page-1), 'current' => 0);
		for($i=$start; $i<=$end; $i++) {
			$current = 0;
			if($i==$page) $current = 1;
			$pager[] = array('name' => $i,'url' => sprintf($url,$i), 'current' => $current);
		}
		$pager[] = array('name' => '<img src="'.$img_path.'ico_next.png" alt="next" >','url' => sprintf($url,$page+1), 'current' => 0);
		$pager[] = array('name' => '<img src="'.$img_path.'ico_last.png" alt="last" >','url' => sprintf($url,$pages), 'current' => 0);
		return $pager;
	}
}
?>