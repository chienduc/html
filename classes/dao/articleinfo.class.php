<?php
/*************************************************************************
Class Article
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 16/04/2012
Coder: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
class ArticleInfo {
	var $id;			# Primary key
	var $store_id;		# Estore id
	var $cat_id;		# Category id
	var $slug;			# Slug
	var $title;			#Title
	var $keyword;		#Keyword
	var $sapo;			# Sapo
	var $detail;		# Detail
	var $viewed;		# Number of views
	var $date_created;	# Date created
	var $date_update;	# Date created
	var $position;
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished
	var $home;			# Display in home page

	# Constructor
	function ArticleInfo($slug, $title, $keyword, $sapo, $detail,$viewed, $date_created, $date_update, $position, $properties, $status, $home, $cat_id = 0, $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->cat_id = $cat_id;
		$this->slug = stripslashes($slug);
		$this->title = stripslashes($title);
		$this->keyword = stripslashes($keyword);
		$this->sapo = stripslashes($sapo);
		$this->detail = stripslashes($detail);
		$this->viewed = $viewed;
		$this->date_created = $date_created;
		$this->date_update = $date_update;
		$this->position = $position;
		$this->properties = unserialize($properties);
		$this->status = $status;
		$this->home = $home;
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
	function getCatId() {
		return $this->cat_id;
	}
	function setCatId($nValue) {
		$this->cat_id=$nValue;
	}
	function getCatSlug() {
		include_once(ROOT_PATH."classes/dao/articlecategories.class.php");
		$articleCategories = new ArticleCategories($this->store_id);
		return $articleCategories->getSlugFromId($this->cat_id);
	}
	function getCatName() {
		include_once(ROOT_PATH."classes/dao/articlecategories.class.php");
		$articleCategories = new ArticleCategories($this->store_id);
		return $articleCategories->getNameFromId($this->cat_id);
	}
	function getSlug() {
		return $this->slug;		
	}
	function setSlug($nValue) {
		$this->slug=stripslashes($nValue);
	}
	function getTitle($lang = 'vn') {
		if($lang == 'vn')	return htmlspecialchars($this->title);
		elseif(isset($this->properties['custom_'.$lang.'_title'])) return $this->properties['custom_'.$lang.'_title'];
	}
	function setTitle($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->title=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_title']=stripslashes($nValue);
	}
	function getKeyword($lang='vn') {
		if($lang=='vn') return $this->keyword;	
		elseif(isset($this->properties['custom_'.$lang.'_keyword'])) return $this->properties['custom_'.$lang.'_keyword'];	
	}
	function setKeyword($nValue,$lang='vn') {
		if($lang=='vn') $this->keyword=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_keyword']=stripslashes($nValue); 
	}
	function getSapo($lang = 'vn') {
		if($lang == 'vn')	return $this->sapo;
		elseif(isset($this->properties['custom_'.$lang.'_sapo'])) return $this->properties['custom_'.$lang.'_sapo'];
	}
	function setSapo($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->sapo=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_sapo']=stripslashes($nValue);
	}
	function getDetails($lang = 'vn') {
		if($lang == 'vn')	return $this->detail;
		else return $this->properties['custom_'.$lang.'_detail'];
	}
	function setDetails($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->detail=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_details']=stripslashes($nValue);
	}
	function getViewed() {
		return $this->viewed;
	}	
	function setViewed($nValue) {
		$this->viewed=$nValue;
	}
	function getDateCreated()
	{
		return $this->date_created;
	}
	function setDateCreated($nValue)
	{
		$this->date_created=$nValue;
	}
	function getDateUpdate()
	{
		return $this->date_update;
	}
	function setDateUpdate($nValue)
	{
		$this->date_update=$nValue;
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
	function getPosition() {
		return $this->position;
	}
	function setPosition($nValue) {
		$this->position = $nValue;
	}
	function getProperties()
	{
		return $this->properties;
	}
	function setProperties($nValue)
	{
		$this->properties=$nValue;
	}
	function getStatus() {
		return $this->status;
	}
	function setStatus($nValue) {
		$this->status = $nValue;
	}
	function getHome() {
		return $this->home;
	}
	function setHome($nValue) {
		$this->home = $nValue;
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getUrl($lang='vn') {
		$url = '';
		if(URL_TYPE == 1 || $page > 1) {	# Query string
			$url = "/$lang/".SCRIPT.'?act=article&id='.$this->id;
			return $url;
		} elseif(URL_TYPE == 2) {	# SEO
			 $url = "/$lang/".$this->getCatSlug().'/'.$this->slug.'-'.$this->id.'.htm';
			return $url;
		} else return '';	
	}
}	
?>