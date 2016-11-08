<?php
/*************************************************************************
Class Cart Item
----------------------------------------------------------------
Bido.vn Project
Last updated: 07/05/2010
Author: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
class CartItem {
	var $id;			# Cart Item ID
	var $store_id;		# Store ID
	var $sess_id;		# Session ID
	var $product_id;	# Product code (primary key)
	var $quantity;		# Quantity
	var $properties;	# Properties (size, color,...)
	var $time_stamp;	# Time stamp

	# Constructor
	function CartItem($product_id, $quantity, $properties, $time_stamp, $sess_id, $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->sess_id = $sess_id;
		$this->product_id = $product_id;
		$this->quantity = $quantity;
		$this->properties = unserialize($properties);
		$this->time_stamp = $time_stamp;
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
	function getSessionId() {
		return $this->sess_id;
	}	
	function setSessionId($nValue) {
		$this->sess_id=$nValue;
	}
	function getProductId() {
		return $this->product_id;
	}	
	function setProductId($nValue) {
		$this->product_id=$nValue;
	}	
	function getQuantity() {
		return $this->quantity;		
	}
	function setQuantity($nValue) {
		$this->quantity=$nValue;
	}
	function getTimeStamp() {
		return $this->time_stamp;		
	}
	function setTimeStamp($nValue) {
		$this->time_stamp=$nValue;
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
	function getProName() {
		include_once(ROOT_PATH."classes/dao/products.class.php");
		$products = new Products($this->store_id);
		return $products->getNameFromId($this->product_id);
	}
	function getProSKU() {
		include_once(ROOT_PATH."classes/dao/products.class.php");
		$products = new Products($this->store_id);
		return $products->getSKUFromId($this->product_id);
	}
	function getProPrice() {
		include_once(ROOT_PATH."classes/dao/products.class.php");
		$products = new Products($this->store_id);
		return $products->getPriceFromId($this->product_id);
	}
	function getsubTotal() {
		include_once(ROOT_PATH."classes/dao/products.class.php");
		$products = new Products($this->store_id);
		return $products->getPriceFromId($this->product_id)*$this->quantity;
	}
	function getProAvatar() {
		include_once(ROOT_PATH."classes/dao/products.class.php");
		$products = new Products($this->store_id);
		$proItem = $products->getObject($this->product_id,'id');
		return $proItem->getProperty('avatar');
	}
	function getProWeight($storeId) {
		include_once(ROOT_PATH."classes/dao/products.class.php");
		$products = new Products($storeId);
		$productItem = $products->getObject($this->product_id,'id');
		if ($productItem) $weight = $productItem->getProperty('weight');
		return $weight;
	}
	
}	
?>