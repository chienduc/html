<?php
/*************************************************************************
Class OrderInfo
----------------------------------------------------------------
BiDo.vn Project
Last updated: 07/11/2010
Author: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
class OrderInfo {
	var $id;			# Order ID
	var $store_id;		# Store ID
	var $user_id;		# Customer ID
	var $user_type;		# 0 - Not registered, 1 - Registered users, 2 - Staff
	var $code;
	var $name;
	var $email;	
	var $address;
	var $province;
	var $tel;
	var $cell;
	var $rname;
	var $raddress;
	var $rprovince;
	var $rtel;
	var $rcell;
	var $rdate;
	var $rnote;
	var $date_created;
	var $last_updated;
	var $properties;
	var $status;

	# Constructor
	function OrderInfo($code,$name, $email, $address, $province, $tel, $cell, $rname, $raddress, $rprovince, $rtel, $rcell, $rdate, $rnote, $date_created, $last_updated, $properties, $status, $user_type = 0, $user_id = 0, $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->user_id = $user_id;
		$this->user_type = $user_type;
		$this->code = $code;
		$this->name = stripslashes($name);
		$this->email = $email;
		$this->address = stripslashes($address);
		$this->province = $province;
		$this->tel = $tel;
		$this->cell = $cell;
		$this->rname = stripslashes($rname);
		$this->raddress = stripslashes($raddress);
		$this->rprovince = $rprovince;
		$this->rtel = $rtel;
		$this->rcell = $rcell;	
		$this->rdate = $rdate;		
		$this->rnote = $rnote;
		$this->date_created = $date_created;		
		$this->last_updated = $last_updated;
		$this->properties = unserialize($properties);
		$this->status = $status;
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
	function getUserId() {
		return $this->user_id;
	}
	function setUserId($nValue) {
		$this->user_id=$nValue;
	}
	function getUserType() {
		return $this->user_type;
	}
	function setUserType($nValue) {
		$this->user_type=$nValue;
	}
	function getCode() {
		return $this->code;
	}	
	function setCode($nValue) {
		$this->code=$nValue;
	}
	function getName() {
		return $this->name;
	}	
	function setName($nValue) {
		$this->name=stripslashes($nValue);
	}
	function getEmail() {
		return $this->email;
	}	
	function setEmail($nValue) {
		$this->email=$nValue;
	}
	function getAddress() {
		return $this->address;
	}	
	function setAddress($nValue) {
		$this->address=stripslashes($nValue);
	}
	function getTel() {
		return $this->tel;
	}	
	function setTel($nValue) {
		$this->tel=$nValue;
	}
	function getCell() {
		return $this->cell;
	}	
	function setCell($nValue) {
		$this->cell=$nValue;
	}
	function getProvince() {
		return $this->province;
	}	
	function setProvince($nValue) {
		$this->province=$nValue;
	}
	function getRName() {
		return $this->rname;
	}	
	function setRName($nValue) {
		$this->rname=stripslashes($nValue);
	}
	function getRAddress() {
		return $this->raddress;
	}	
	function setRAddress($nValue) {
		$this->raddress=stripslashes($nValue);
	}
	function getRTel() {
		return $this->rtel;
	}
	function setRTel($nValue) {
		$this->rtel=$nValue;
	}
	function getRCell() {
		return $this->rcell;
	}	
	function setRCell($nValue) {
		$this->rcell=$nValue;
	}
	function getRDate() {
		return $this->rdate;
	}	
	function setRDate($nValue) {
		$this->rdate=$nValue;
	}
	function getRProvince() {
		return $this->rprovince;
	}	
	function setRProvince($nValue) {
		$this->rprovince=$nValue;
	}
	function getRNote() {
		return $this->rnote;
	}	
	function setRNote($nValue) {
		$this->rnote=$nValue;
	}
	function getDateCreated() {
		return $this->date_created;
	}	
	function setDateCreated($nValue) {
		$this->date_created=$nValue;
	}
	function getLastUpdated() {
		return $this->last_updated;
	}	
	function setLastUpdated($nValue) {
		$this->last_updated=$nValue;
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
		$this->status=$nValue;
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status_payment'][$this->status];
	}
	function getOrderTotal() {
		include_once(ROOT_PATH."classes/dao/orderitems.class.php");
		$orderItems = new OrderItems($this->id);
		return $orderItems->getsum();
	}
	
	function getOrderProvince() {
		include_once(ROOT_PATH."classes/dao/provinces.class.php");
		$provinces = new Provinces();
		return $provinces->getNameFromId($this->province);
	}
	function getOrderRProvince() {
		include_once(ROOT_PATH."classes/dao/areas.class.php");
		$areas = new Areas();
		return $areas->getNameFromId($this->rprovince);
	}
	function getOrderShipPrice() {
		include_once(ROOT_PATH."classes/dao/areas.class.php");
		$areas = new Areas($this->store_id);
		$areaObject = $areas->getObject($this->rprovince,'id');
		return $areaObject->getPrice()*$areaObject->getShipPrice();
	}
	function getOrderCost() {
		include_once(ROOT_PATH."classes/dao/estores.class.php");
		$estore = new EStores();
		$estoreInfo = $estore->getObject($this->store_id);
		$vat = $estoreInfo->getProperty('order_vat');	// order vat 
		
		include_once(ROOT_PATH."classes/dao/areas.class.php");
		$areas = new Areas($this->store_id);
		$areaObject = $areas->getObject($this->rprovince,'id');
		if($areaObject) $price = $areaObject->getShipPrice();	// shipping price in area

		include_once(ROOT_PATH."classes/dao/orderitems.class.php");
		$orderItems = new OrderItems($this->id);
		$carts = $orderItems->getObjects(1,"`order_id` = '".$this->id."'",array('id'=>'ASC'),'');
		
		$subWeight = 0;
		$grandTotal = 0;
		foreach($carts as $cart){
			$weight = $cart->getProWeight($this->store_id) * $cart->getQuantity();
			$subWeight = $subWeight + $weight;	// total weight of product on order 
			$priceTotal = $cart->getPrice() * $cart->getQuantity();
			$grandTotal = $grandTotal + $priceTotal;	// total price of product on order
		} 
		$vatFrice = ($grandTotal*$vat)/100;	// vat on order 
		$shippingPrice = $price*$subWeight;	// shipping price on order 
		$subgrandTotal = $grandTotal + $shippingPrice + $vatFrice;	// total price of order 
		$costs = array('shipping' => $shippingPrice, 'grandTotal' => $grandTotal, 'vatFrice' => $vatFrice, 'subgrandTotal' => $subgrandTotal);
		return $costs;
	}
	function getUrl() {
		$url = '';
		if(URL_TYPE == 1) {			# Query string
			$url = '/'.SCRIPT."?op=orderInfo&oId=".$this->id;		
			return $url;
		} elseif(URL_TYPE == 2) {	# SEO
			$url = '/o'.$this->id;
			return $url;		
		} else return '';	
	}
}	
?>