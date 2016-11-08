<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class ClientsInfo {
	var $id;			
	var $store_id;				
	var $name;		
	var $status;		# 0-Disabled, 1-Active, 2-Deleted
	var $position;		# Display order
	var $viewed;		# Number of views
	var $date_created;	# Date created
	var $properties;	# Properties
	
	# Constructor
	function ClientsInfo($name, $status=0, $position=0, $viewed=0,$date_created='',$properties='', $store_id=0, $id = 0)
	{
		$this->id = $id;
		$this->store_id=$store_id;
		$this->name = $name;
		$this->status = $status;
		$this->position = $position;
		$this->viewed = $viewed;
		$this->date_created = $date_created;
		$this->properties = unserialize($properties);
	}

	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}	
	
	function getName() {
		return $this->name;		
	}
	function setName($nValue) {
		$this->name=stripslashes($nValue);
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
	function getUrl() {
		if(isset($this->properties['pdf'])) return "/upload/1/clients/pdf/".$this->properties['pdf'];
		else return '';
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