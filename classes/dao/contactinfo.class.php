<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class ContactInfo {
	var $id;			# Primary key
	var $name;          # Name questioned
	var $slug;			# Slug
	var $title;			# Category title
	var $address;
	var $email;	
	var $tel;	
	var $fax;
	var $ip;
	var $detail;
	var $position;
	var $viewed;
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished

	# Constructor
	function ContactInfo($name, $slug, $title ,$address ,$email ,$tel ,$fax, $ip, $detail ,$position ,$viewed, $properties, $status, $id = 0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->slug = $slug;
		$this->address = $address;
		$this->email = $email;
		$this->tel = $tel;
		$this->fax = $fax;
		$this->ip = $ip;
		$this->title = stripslashes($title);
		$this->viewed = $viewed;
		$this->detail = $detail;
		$this->position = $position;
		$this->properties = unserialize($properties);
		$this->status = $status;
	}
	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
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
	function getTitle($lang = 'vn') {
		if($lang == 'vn')	return $this->title;
		else return $this->properties['custom_'.$lang.'_title'];
	}
	function setTitle($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->title=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_title']=stripslashes($nValue);
	}
	function getDetails($lang = 'vn') {
		if($lang == 'vn')	return $this->detail;
		else return $this->properties['custom_'.$lang.'_detail'];
	}
	function setDetails($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->detail=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_detail']=stripslashes($nValue);
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
	function getIp() {
		return $this->ip;
	}	
	function setIp($nValue) {
		$this->ip=$nValue;
	}
	function getPosition() {
		return $this->position;
	}	
	function setPosition($nValue) {
		$this->position=$nValue;
	}
	function getFax() {
		return $this->fax;
	}	
	function setDateCreated($nValue) {
		$this->fax=$nValue;
	}
	function getAddress($lang='vn') {
		if($lang=='vn') return $this->address;
		elseif(isset($this->properties['custom_'.$lang.'_address'])) return $this->properties['custom_'.$lang.'_address'];
	}	
	function setAddress($nValue,$lang='vn') {
		if($lang=='vn') $this->address=$nValue;
		else $this->properties['custom_'.$lang.'_address']=$nValue;
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
	function getViewed() {
		return $this->viewed;
	}
	function setViewed($nValue) {
		$this->viewed = $nValue;
	}
	
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getUrl($lang='vn') {
		    $url = "/$lang/contact/".$this->slug.'.html';
			return $url;	
	}
	
}	
?>