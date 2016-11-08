<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class EmailTemplateInfo {
	var $Id;			# email code (primary key)
	var $store_id;		
	var $name;			# Name method
	var $title;			# Email title
	var $content;		# Email content
	var $properties;		# List of tokens
	var $status;		# 0-Disabled, 1-Active, 2-Deleted
	# Constructor
	function EmailTemplateInfo($name, $title, $content, $properties, $status=0,$store_id=0, $Id = 0)
	{
		$this->Id = $Id;
		$this->store_id=$store_id;
		$this->name = $name;
		$this->title = $title;
		$this->content = stripslashes($content);
		$this->properties = unserialize($properties);
		$this->status = $status;
	}

	function getId() {
		return $this->Id;
	}	
	function setId($nValue) {
		$this->Id=$nValue;
	}	
	
	function getName() {
		return $this->name;
	}	
	function setName($nValue) {
		$this->name=$nValue;
	}
	
	
	function getTitle() {
		return $this->title;
	}	
	function setTitle($nValue) {
		$this->title=$nValue;
	}
	function getContent() {
		return $this->content;
	}
	function setContent($nValue) {
		$this->content=stripslashes($nValue);
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