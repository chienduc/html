<?php
/*************************************************************************
Class Areas
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd                                  
Last updated: 7/5/2012
Coder: Tran Thi My Xuyen
**************************************************************************/
class AreaInfo {
	var $Id;			# Area code (primary key)
	var $store_id;		
	var $aid;			# Group of the area
	var $ship_id;		# Shipping method ID
	var $name;			# Name area
	var $times;			# Time area
	var $price;			# Price area
	var $status;		# 0-Disabled, 1-Active, 2-Deleted
	var $position;		# Display order
	var $properties;	# Properties
	# Constructor
	function AreaInfo($name, $times, $price, $status=0, $position=0, $properties='',$aid = 0,$ship_id=0,$store_id=0, $Id = 0)
	{
		$this->Id = $Id;
		$this->store_id=$store_id;
		$this->ship_id=$ship_id;
		$this->aid = $aid;
		$this->name = $name;
		$this->times = $times;
		$this->price = $price;
		$this->status = $status;
		$this->position = $position;
		$this->properties = unserialize($properties);
	}

	function getId() {
		return $this->Id;
	}	
	function setId($nValue) {
		$this->Id=$nValue;
	}
	
	function getShipId() {
		return $this->ship_id;
	}	
	function setShipId($nValue) {
		$this->ship_id=$nValue;
	}
	function getShipName() {
		include_once(ROOT_PATH."classes/dao/shippingmethods.class.php");
		$shippingMethods = new ShippingMethods($this->store_id);
		return $shippingMethods->getNameFromId($this->ship_id);
	}
	
	function getAId() {
		return $this->aid;		
	}
	function setAId($nValue) {
		$this->aid=$nValue;
	}
	
	function getName() {
		return $this->name;
	}	
	function setName($nValue) {
		$this->name=$nValue;
	}
	function getTime()
	{
		return $this->times;
	}
	function setTime($nValue)
	{
		$this->times=$nValue;
	}
	function getPrice() {
		return $this->price;		
	}
	function setPrice($nValue) {
		$this->price=$nValue;
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
	
	function getStatus() {
		return $this->status;
	}
	function setStatus($nValue) {
		$this->status = $nValue;
	}
	function getPosition() {
		return $this->position;
	}
	function setPosition($nValue) {
		$this->position = $nValue;
	}
	function getStatusText() {
		global $amessages;
		return $amessages['status_text'][$this->status];
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getShipPrice() {
		include_once(ROOT_PATH."classes/dao/shippingmethods.class.php");
		$shippingmethods = new ShippingMethods($this->store_id);
		$price = $shippingmethods->getPriceFromId($this->ship_id);
		return $price * $this->price;
	}
}	
?>