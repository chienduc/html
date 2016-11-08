<?php
/*************************************************************************
Class Customer
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd
Last updated: 09/09/2011
Coder: Tran Thi My Xuyen
**************************************************************************/
class BusinessInfo {
	var $id;			# Primary key
	var $store_id;		# Estore id
	var $area_id;		# Area id
	var $type;			# Type user
	var $username;		# Username
	var $password;		# Password
	var $fullname;		# Fullname
	var $address;		# Address
	var $email;			# Email
	var $tel;			# Tel
	var $properties;	# Properties(about, cel)
	var $date_created;	# Date created
	var $last_login;	# Date Last login
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished
	var $slug;

	# Constructor
	function BusinessInfo($slug,$type, $username, $password, $fullname,$address,$email, $tel, $properties, $date_created, $last_login, $status, $area_id=0, $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->slug = $slug;
		$this->area_id = $area_id;
		$this->type = $type;
		$this->username = trim($username);
		$this->password = $password;
		$this->fullname = $fullname;
		$this->address = $address;
		$this->email = $email;
		$this->tel = $tel;
		$this->properties = unserialize($properties);
		$this->date_created = $date_created;
		$this->last_login = $last_login;
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
	function getAreaId() {
		return $this->area_id;
	}
	function setSlug($nValue) {
		$this->slug=$nValue;
	}
	function getSlug() {
		return $this->slug;
	}
	
	function setAreaId($nValue) {
		$this->area_id=$nValue;
	}
	function getType() {
		return $this->type;
	}
	function setType($nValue) {
		$this->type=$nValue;
	}
	function getUsername() {
		return $this->username;		
	}
	function setUsername($nValue) {
		$this->username=$nValue;
	}
	function getPassword() {
		return $this->password;		
	}
	function setPassword($nValue) {
		$this->password=$nValue;
	}
	function getFullName() {
		return $this->fullname;		
	}
	function setFullName($nValue) {
		$this->fullname=$nValue;
	}
	function getAddress() {
		return $this->address;		
	}
	function setAddress($nValue) {
		$this->address=$nValue;
	}
	function getEmail() {
		return $this->email;		
	}
	function setEmail($nValue) {
		$this->email=$nValue;
	}
	function getTel() {
		return $this->tel;		
	}
	function setTel($nValue) {
		$this->tel=$nValue;
	}
	
	function getDateCreated()
	{
		return $this->date_created;
	}
	function setDateCreated($nValue)
	{
		$this->date_created=$nValue;
	}
	function getLastLogin()
	{
		return $this->last_login;
	}
	function setLastLogin($nValue)
	{
		$this->last_login=$nValue;
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
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getUrl($lang='vn') {
		$url = "/$lang/business/".$this->slug.'-c'.$this->id.'.html';
			return $url;
		
	}
	
	
}	
?>