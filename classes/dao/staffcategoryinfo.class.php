<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/

class StaffCategoryInfo {
	var $id;			# Primary key
	var $parent_id;		# Parent category
	var $store_id;		# Estore id
	var $slug;			# Slug
	var $name;			# Category name
	var $keyword;		# Cagegory keyword
	var $sapo;			# Category sapo
	var $position;		# Position
	var $viewed;		# Number of views
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished

	# Constructor
	function StaffCategoryInfo($slug, $name, $keyword, $sapo, $position, $viewed, $properties, $status, $store_id = 0, $parent_id = 0, $id = 0)
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
		if($lang == 'vn')	return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];	
	}
	function setName($nValue,$lang='vn') {
		if($lang == 'vn')	$this->name=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
	}
	function getKeyword($lang='vn') {
		if($lang == 'vn') return $this->keyword;	
		else return $this->properties['custom_'.$lang.'_keyword'];		
	}
	function setKeyword($nValue,$lang='vn') {
		if($lang=='vn') $this->keyword=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_keyword']=stripslashes($nValue);
	}
	function getSapo($lang='vn') {
		if($lang=='vn') return $this->sapo;		
		else return $this->properties['custom_'.$lang.'_sapo'];	
	}
	function setSapo($nValue,$lang='vn') {
		if($lang=='vn') $this->sapo=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_sapo']=stripslashes($nValue);
	}
	function getPosition() {
		return $this->position;
	}	
	function setPosition($nValue) {
		$this->position=$nValue;
	}
	
	function getActiveProducts() {
		include_once(ROOT_PATH."classes/dao/productes.class.php");
		$products = new Products($this->store_id);
		$rowsPages = $products->getNumItems('id', "`cat_id` = '".$this->id."' AND `status` = '1'");
		return $rowsPages['rows'];
	}	
	function getNumProducts() {
		include_once(ROOT_PATH."classes/dao/products.class.php");
		$products = new Products($this->store_id);
		$rowsPages = $products->getNumItems('id', "`cat_id` = '".$this->id."'");
		return $rowsPages['rows'];
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
		$this->properties[$key]=stripslashes($nValue);
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
		return $amessages['status_product'][$this->status];
	}
	function getUrl($lang ='vn') {
		$url = '';
		$url = '/'.$lang.'/staffs/'.$this->slug.'-c'.$this->id.'.html';
			return $url;
	
	}
	
	function getChildren($page = 1, $condition = "`status` = '1'", $sort = array('position' => 'asc'), $items_per_page = 100) {
		include_once(ROOT_PATH."classes/dao/productcategories.class.php");
		$productCategories = new ProductCategories($this->store_id);
		$productCategoryItems = $productCategories->getObjects($page,"`parent_id` = '".$this->id."' AND $condition",$sort,items_per_page);
		return $productCategoryItems;
	}
}	
?>