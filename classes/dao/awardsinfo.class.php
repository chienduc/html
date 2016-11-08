<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class AwardsInfo {
	var $id;			
	var $store_id;				
	var $name;		
	var $status;		# 0-Disabled, 1-Active, 2-Deleted
	var $position;		# Display order
	var $viewed;		# Number of views
	var $home;
	var $date_created;	# Date created
	var $properties;	# Properties
	
	# Constructor
	function AwardsInfo($name, $status=0, $position=0, $viewed=0, $home, $date_created='',$properties='', $store_id=0, $id = 0)
	{
		$this->id = $id;
		$this->store_id=$store_id;
		$this->name = $name;
		$this->status = $status;
		$this->position = $position;
		$this->viewed = $viewed;
		$this->home = $home;
		$this->date_created = $date_created;
		$this->properties = unserialize($properties);
	}

	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}	
	
	function getName($lang='vn') {
		if($lang == 'vn')	return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];	
	}
	function setName($nValue,$lang='vn') {
		if($lang == 'vn')	$this->name=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
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
	function getHone() {
		return $this->home;
	}
	function setHome($nValue) {
		$this->home = $nValue;
	}
	function getUrl($lang='vn') {
		return "/$lang/awards-and-honors.html";
	}
	function getStatusText() {
		global $amessages;
		return $amessages['status_text'][$this->status];
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
}	
?>