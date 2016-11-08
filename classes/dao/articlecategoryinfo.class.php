<?php
/*************************************************************************
Class ArticleCategory
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd
Last updated: 09/09/2010
Author: Tran Thi My Xuyen
Checked by: Mai Minh (22/09/2011)
**************************************************************************/
class ArticleCategoryInfo {
	var $id;			# Primary key
	var $parent_id;		# Parent category
	var $store_id;		# Estore id
	var $slug;			# Slug
	var $name;			# Category name
	var $keyword;		#Keyword
	var $sapo;			# Sapo
	var $position;		# Position
	var $viewed;		# Number of views
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished

	# Constructor
	function ArticleCategoryInfo($slug, $name, $keyword, $sapo, $position, $viewed, $properties, $status, $store_id = 0, $parent_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->parent_id = $parent_id;
		$this->store_id = $store_id;
		$this->slug = $slug;
		$this->name = stripslashes($name);
		$this->keyword = stripslashes($keyword);
		$this->sapo = stripslashes($sapo);
		$this->position = $position;
		$this->viewed = $viewed;
		$this->properties = unserialize($properties);
		$this->status = $status;
	}
	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}
	function getParentId() {
		return $this->parent_id;
	}
	function setParentId($nValue) {
		$this->parent_id=$nValue;
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
		$this->slug=$nValue;
	}
	function getName($lang='vn') {
		if($lang=='vn')	return $this->name;
		elseif(isset($this->properties['custom_'.$lang.'_name'])) return $this->properties['custom_'.$lang.'_name'];
	}
	function setName($nValue,$lang='vn') {
		if($lang=='vn') $this->name=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
	}
	function getKeyword($lang='vn') {
		if($lang=='vn')	return $this->keyword;
		elseif(isset($this->properties['custom_'.$lang.'_keyword'])) return $this->properties['custom_'.$lang.'_keyword'];
	}
	function setKeyword($nValue) {
		
		if($lang=='vn')$this->keyword=stripslashes($nValue);
		else  $this->properties['custom_'.$lang.'_keyword']=stripslashes($nValue);
	}
	function getSapo($lang='vn') {
		if($lang=='vn')	return $this->sapo;
		elseif(isset($this->properties['custom_'.$lang.'_sapo'])) return $this->properties['custom_'.$lang.'_sapo'];
	}
	function setSapo($nValue,$lang='vn') {
		if($lang=='vn')$this->sapo=stripslashes($nValue);		
		else  $this->properties['custom_'.$lang.'_sapo']=stripslashes($nValue);
	}
	function getPosition() {
		return $this->position;
	}	
	function setPosition($nValue) {
		$this->position=$nValue;
	}
	function getNumArticles() {
		include_once(ROOT_PATH."classes/dao/articles.class.php");
		$articles = new Articles($this->store_id);
		$rowsPages = $articles->getNumItems('id', "`cat_id` = '".$this->id."'");
		return $rowsPages['rows'];
	}
	function getNumActiveArticles() {
		include_once(ROOT_PATH."classes/dao/articles.class.php");
		$articles = new Articles($this->store_id);
		$rowsPages = $articles->getNumItems('id', "`cat_id` = '".$this->id."' AND `status` ='1'");
		return $rowsPages['rows'];
	}
	function getListArticles() {
		include_once(ROOT_PATH."classes/dao/articles.class.php");
		$articles = new Articles($this->store_id);
		$listNew = $articles->getObjects(1,"`status`=1 AND `cat_id`='".$this->id."'",array("date_created"=>"DESC"),5);
		return $listNew;
	}
	function getViewed() {
		return $this->viewed;
	}	
	function setViewed($nValue) {
		$this->viewed=$nValue;
	}
	function getProperty($key)
	{
		if(isset($this->properties[$key])) return ''.$this->properties[$key];
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
	function getStatus() {
		return $this->status;
	}
	function setStatus($nValue) {
		$this->status = $nValue;
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getUrl($lang='vn',$page = 1, $keywords = '', $sort_key = 'position', $sort_direction = 'asc') {
		$url = '';
		if(URL_TYPE == 1 || $page > 1) {	# Query string
			$url = '/'.SCRIPT.'?act=category&id='.$this->id.'&pg='.$page.'&kw='.$keywords.'&sk='.$sort_key.'&sd='.$sort_direction.'&lang='.$lang;				
			return $url;
		} elseif(URL_TYPE == 2) {	# SEO
			$url = "/$lang/news/".$this->slug.'-c'.$this->id.($page>1?'-p'.$page:'').'.html';			
			return $url;
		
		} else return '';	
	}
	function getChildren($page = 1, $condition = "`status` = '1'", $sort = array('position' => 'asc'), $items_per_page = 100) {
		include_once(ROOT_PATH."classes/dao/articlecategories.class.php");
		$articleCategories = new ArticleCategories($this->store_id);
		$articleCategoryItems = $articleCategories->getObjects($page,"`parent_id` = '".$this->id."' AND $condition",$sort,items_per_page);
		return $articleCategoryItems;
	}
	
}	
?>