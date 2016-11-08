<?php
/*************************************************************************
Class Staticinfo
----------------------------------------------------------------
Bido.vn Project
Company: Derasoft Co., Ltd                                  
Last updated: 15/09/2011
Coder: Tran Thi My Xuyen                                   
**************************************************************************/
class Staticinfo {
	var $id;			# Primary key
	var $store_id;		# Estore Id 
	var $slug;			# Slug
	var $block;			# Slug
	var $title;			# Title
	var $keywords;		# Keywords
	var $sapo;			# Sapo
	var $details;		# Details
	var $date_created;	# Date created
	var $status;		# Status
	var $position;		#Position
	var $properties;	# Properties
	function Staticinfo ($slug='',$block = '', $title='', $keywords,$sapo='',$details, $date_created, $status='',$position,  $properties = '', $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->slug = $slug;
		$this->block = $block;
		$this->title = stripslashes($title);
		$this->keywords = stripslashes($keywords);
		$this->sapo = stripslashes($sapo);
		$this->details = stripslashes($details);
		$this->date_created = $date_created;
		$this->status = $status;
		$this->position = $position;
		$this->properties = unserialize($properties);
	}

	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}
	function getStoreId() {
		return $this->store_id;
	}
	function setStoreId($nValue) {
		$this->store_id=$nValue;
	}
	function getSlug() {
		return $this->slug;
	}	
	function setSlug($nValue) {
		$this->$slug=stripslashes($nValue);
	}
	function getBlock() {
		return $this->block;
	}	
	function setBlock($nValue) {
		$this->$block=$nValue;
	}
	function getTitle($lang = 'vn') {
		if($lang == 'vn')	return $this->title;
		else return $this->properties['custom_'.$lang.'_title'];
	}
	function setTitle($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->title=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_title']=stripslashes($nValue);
	}
	function getKeywords($lang='vn') {
		if($lang=='vn') return $this->keywords;
		else return $this->properties['custom_'.$lang.'_keyword'];
	}
	function setKeywords( $nValue,$lang='vn') {
		if($lang=='vn') $this->$keywords=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_keyword']=stripslashes($nValue);
	}
	function getSapo($lang = 'vn') {
		if($lang == 'vn')	return $this->sapo;
		else return $this->properties['custom_'.$lang.'_description'];
	}
	function setSapo($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->sapo=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_description']=stripslashes($nValue);
	}
	function getDetails($lang = 'vn') {
		if($lang == 'vn')	return $this->details;
		else return $this->properties['custom_'.$lang.'_detail'];
	}
	function setDetails($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->detail=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_detail']=stripslashes($nValue);
	}
	function getDateCreated() {
		return $this->date_created;
	}
	function setDateCreated( $nVlaue) {
		$this->$date_created=$nValue;
	}
	function getStatus() {
		return $this->status;
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}	
	function setStatus($nValue) {
		$this->$status = $nValue;
	}
	function isEnabled() {
		return ($this->status == 1?1:0);
	}
	function isDeleted() {
		return ($this->status == 2?1:0);
	}
	function isDisabled() {
		return ($this->status == 0?1:0);
	}
	function getPosition() {
		return $this->position;
	}
	function setPosition($nValue) {
		$this->position = $nValue;
	}
	function getProperty($key)
	{
		if(isset($this->properties[$key])) return $this->properties[$key];
		return '';
	}
	function setProperty($key,$nValue)
	{
		$this->properties[$key]=$nValue;
	}
	function getProperties()
	{
		return $this->properties;
	}
	function setProperties($nValue)
	{
		$this->properties=$nValue;
	}
	function isImage() {
		$img = $this->avatar;
		if($this->avatar) $img = $this->avatar;
		if(preg_match("/jpg|JPEG|png|bmp|gif/",$img)) return 1;
		return 0;
	}
	function isFlash() {
		$img = $this->avatar;
		if($this->avatar) $img = $this->avatar;
		if(preg_match("/.swf/",$img)) return 1;
		return 0;
	}
	function getUrl() {
		$url = '';
		if(URL_TYPE == 1 || $page > 1) {	# Query string
			$url = '/'.SCRIPT.'?act=static&id='.$this->id;
			return $url;
		} elseif(URL_TYPE == 2) {	# SEO
			$url = '/info/'.$this->slug.'.html';
			return $url;
		} else return '';	
	}
}	
?>