<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class ExtendInfo {
	var $id;			# Primary key
	var $name;          # Name 
	var $url;			# Url
	var $detail;
	var $position;
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished

	# Constructor
	function ExtendInfo($name, $url, $detail ,$position, $properties, $status, $id = 0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->url = $url;
		$this->position = $position;
		$this->detail = stripslashes($detail);
		$this->properties = unserialize($properties);
		$this->status = $status;
	}
	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}
	function getUrl($lang='vn') {
		if($lang=='vn') return $this->url;
		elseif(isset($this->properties['custom_'.$lang.'_url'])) return $this->properties['custom_'.$lang.'_url'];
	}	
	function setUrl($nValue,$lang='vn') {
		if($lang=='vn') $this->url=$nValue;
		else $this->properties['custom_'.$lang.'_url']=$nValue;
	}
	function getName($lang='vn') {
		if($lang == 'vn')	return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];	
	}
	function setName($nValue,$lang='vn') {
		if($lang == 'vn')	$this->name=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
	}
	
	function getDetails($lang = 'vn') {
		if($lang == 'vn')	return $this->detail;
		else return $this->properties['custom_'.$lang.'_detail'];
	}
	function setDetails($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->detail=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_detail']=stripslashes($nValue);
	}
	
	function getPosition() {
		return $this->position;
	}	
	function setPosition($nValue) {
		$this->position=$nValue;
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

	
}	
?>