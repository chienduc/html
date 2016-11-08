<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class RequestInfo {
	var $id;			# Primary key
	var $name;          # Name questioned
	var $slug;			# Slug
	var $title;			# Category title
	var $answer;
	var $email;	
	var $id_service;	
	var $tel;	
	var $created;
	var $viewed;
	var $home;
	var $position;		# Position
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished
	var $country;
	var $number;
	var $txtdate;
	var $listcate;
	var $listhotel;

	# Constructor
	function RequestInfo($id_service,$name, $slug, $title ,$answer ,$email ,$tel ,$listcate,$listhotel,$country, $number, $txtdate, $created ,$viewed, $home, $position, $properties, $status, $id = 0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->id_service = $id_service;
		$this->slug = $slug;
		$this->answer = $answer;
		$this->email = $email;
		$this->tel = $tel;
		$this->listcate = $listcate;
		$this->listhotel = $listhotel;
		$this->country = $country;
		$this->number = $number;
		$this->txtdate = $txtdate;
		$this->created = $created;
		$this->title = stripslashes($title);
		$this->position = $position;
		$this->viewed = $viewed;
		$this->home = $home;
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
	function getIdService() {
		return $this->id_service;
	}	
	function setIdService($nValue) {
		$this->id_service=$nValue;
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
		if($lang == 'vn')	return $this->answer;
		else return $this->properties['custom_'.$lang.'_answer'];
	}
	function setDetails($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->answer=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_answer']=stripslashes($nValue);
	}
	function getPosition() {
		return $this->position;
	}	
	function setPosition($nValue) {
		$this->position=$nValue;
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
	
	function getListCate() {
		return $this->listcate;
	}	
	function setListCate($nValue) {
		$this->listcate=$nValue;
	}
	
	function getListHotel() {
		return $this->listhotel;
	}	
	function setListHotel($nValue) {
		$this->listhotel=$nValue;
	}
	
	function getCountry() {
		return $this->country;
	}	
	function setCountry($nValue) {
		$this->country=$nValue;
	}
	
	function getNumber() {
		return $this->number;
	}	
	function setNumber($nValue) {
		$this->number=$nValue;
	}
	
	function getDate() {
		return $this->txtdate;
	}	
	function setDate($nValue) {
		$this->txtdate=$nValue;
	}
	function getDateCreated() {
		return $this->created;
	}	
	function setDateCreated($nValue) {
		$this->created=$nValue;
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
	function getViewed() {
		return $this->viewed;
	}
	function setViewed($nValue) {
		$this->viewed = $nValue;
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
	function getNameService() {
		include_once(ROOT_PATH."classes/dao/services.class.php");
		$services = new Services($this->store_id);
		return $services->getNameFromId($this->id_service);
	}
	
	
}	
?>