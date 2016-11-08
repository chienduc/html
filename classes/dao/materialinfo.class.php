<?php
/*************************************************************************
Class Product
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd
Last updated: 06/24/2010
Author: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
class MaterialInfo {
	var $id;			# Primary key
	var $store_id;		# Estore id
	var $cat_id;		# Category id
	var $sku;			# SKU
	var $brand;
	var $slug;			# Slug
	var $name;			# Product name
	var $address;
	var $client;
	var $completed;
	var $keyword;		# Product keyword
	var $description;	# Description
	var $detail;		# Detail
	var $avatar;		# avatar
	var $price;			# Price
	var $market_price;	# Market price
	var $currency;		# Currency
	var $viewed;		# Number of views
	var $date_created;	# Date created
	var $updated;		# Date update
	var $position;
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished
	var $home;
	# Constructor
	function MaterialInfo($sku, $brand, $slug, $name, $address, $client, $completed, $keyword, $description, $detail, $avatar, $price, $market_price, $currency, $viewed, $date_created, $updated, $position, $properties, $status, $home, $cat_id = 0, $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->cat_id = $cat_id;
		$this->brand = $brand;
		$this->sku = stripslashes(htmlspecialchars($sku));
		$this->slug = stripslashes(htmlspecialchars($slug));
		$this->name = stripslashes(htmlspecialchars($name));
		$this->address = stripslashes($address);
		$this->client = stripslashes(htmlspecialchars($client));
		$this->completed = $completed;
		$this->keyword = stripslashes(htmlspecialchars($keyword));
		$this->description = stripslashes(htmlspecialchars($description));
		$this->detail = stripslashes($detail);
		$this->avatar = $avatar;
		$this->price = $price;
		$this->market_price = $market_price;
		$this->currency = $currency;
		$this->viewed = $viewed;
		$this->date_created = $date_created;
		$this->updated = $updated;
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
	
	function getBrand() {
		return $this->brand;
	}
	
	function setBrand($nValue) {
		$this->brand=$nValue;
	}
	
	function getCatSlug() {
		include_once(ROOT_PATH."classes/dao/materialcategories.class.php");
		$materialCategories = new MaterialCategories($this->store_id);
		return $materialCategories->getSlugFromId($this->cat_id);
	}
	function getCatName() {
		include_once(ROOT_PATH."classes/dao/materialcategories.class.php");
		$materialCategories = new MaterialCategories($this->store_id);
		return $materialCategories->getNameFromId($this->cat_id);
	}
	function getNameBusiness() {
		include_once(ROOT_PATH."classes/dao/businesss.class.php");
		$businesss = new Businesss($this->store_id);
		if($this->brand) return $businesss->getNameFromId($this->brand);   
		else return '';
	}
	function getUrlBusiness() {
		include_once(ROOT_PATH."classes/dao/businesss.class.php");
		$businesss = new Businesss($this->store_id);
		if(!$this->brand) return '';
		$businesssI = $businesss->getObject($this->brand,'id'); 
		return $businesssI->getUrl();   
	}
	function getSku() {
		return $this->sku;		
	}
	function setSku($nValue) {
		$this->sku=stripslashes($nValue);
	}
	function getSlug() {
		return $this->slug;		
	}
	function setSlug($nValue) {
		$this->slug=stripslashes($nValue);
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
		if($lang=='vn') return $this->keyword;	
		elseif(isset($this->properties['custom_'.$lang.'_keyword'])) return $this->properties['custom_'.$lang.'_keyword'];	
	}
	function setKeyword($nValue,$lang='vn') {
		if($lang == 'vn')	$this->keyword=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_keyword']=stripslashes($nValue);
	}
	function getAddress($lang='vn') {
		if($lang=='vn') return $this->address;	
		elseif(isset($this->properties['custom_'.$lang.'_address'])) return $this->properties['custom_'.$lang.'_address'];		
	}
	function setAddress($nValue,$lang='vn') {
		if($lang=='vn') $this->address=stripslashes($nValue);
		else  $this->properties['custom_'.$lang.'_address']=stripslashes($nValue);
	}
	function getClient($lang='vn') {
		if($lang=='vn') return $this->client;	
		elseif(isset($this->properties['custom_'.$lang.'_client'])) return $this->properties['custom_'.$lang.'_client'];	
	}
	function setClient($nValue,$lang='vn') {
		if($lang=='vn') $this->client=stripslashes($nValue);
		else  $this->properties['custom_'.$lang.'_client']=stripslashes($nValue);
	}
	function getCompleted() {
		return Changeundate($this->completed);		
	}
	function setCompleted($nValue) {
		$this->completed=stripslashes($nValue);
	}
	function getDescription($lang='vn') {
	if($lang == 'vn')	return $this->description;
		else return $this->properties['custom_'.$lang.'_description'];
		}
	function setDescription($nValue,$lang='vn') {
		if($lang == 'vn')	$this->description=stripslashes($nValue);
		else  $this->properties['custom_'.$lang.'_name']=stripslashes($nValue);;	
	}
	function getDetail($lang='vn') {
		if($lang == 'vn')	return $this->detail;
		else return $this->properties['custom_'.$lang.'_detail'];
	}
	function setDetail($nValue,$lang='vn') {
		
		if($lang == 'vn')$this->detail=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_detail']=stripslashes($nValue);;
	}
	function getAvatar() {
		$photos = $this->properties['photos'];
		if($photos) return $photos[0];
		return '';
	}
	function setAvatar($nValue) {
		$this->avatar=$nValue;
	}
	function getPrice() {
		return $this->price;
	}	
	function setPrice($nValue) {
		$this->price=$nValue;
	}
	function getMarketPrice() {
		return $this->market_price;
	}
	function setMarketPrice($nValue) {
		$this->market_price=$nValue;
	}
	function getCurrency() {
		return $this->currency;
	}	
	function setCurrency($nValue) {
		$this->currency=$nValue;
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
	function getUpdated()
	{
		return $this->updated;
	}
	function setUpdated($nValue)
	{
		$this->updated=$nValue;
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
		return $amessages['status_product'][$this->status];
	}
	# Return 1 if File is not null
	function getNullFile($n) {
		for($i=1;$i<=$n;$i++){
		$key = "file".$i;
		if($this->$key!='')
			return 1;
		}
		return '';
	}
	function getUrl($lang='vn'){
     	$url = "/$lang/materials/".$this->getCatSlug().'/'.$this->slug.'-p'.$this->id.'.html';
		return $url;
		
	}
	
}	
?>