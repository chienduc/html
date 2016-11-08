<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class TypetourInfo {
	var $id;			# Primary key
	var $slug;			# Slug
	var $title;			# Category title
	var $position;		# Position
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished

	# Constructor
	function TypetourInfo($title, $slug, $position, $status, $id = 0)
	{
		$this->id = $id;
		$this->slug = $slug;
		$this->title = stripslashes($title);
		$this->position = $position;
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
	
	function getTitle($lang = 'vn') {
		if($lang == 'vn')	return $this->title;
		else return $this->properties['custom_'.$lang.'_title'];
	}
	function setTitle($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->title=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_title']=stripslashes($nValue);
	}
	
	function getPosition() {
		return $this->position;
	}	
	function setPosition($nValue) {
		$this->position=$nValue;
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
		$url = "/$lang/question/".$this->slug.'-'.$this->id.'.htm';
		return $url;
			
	}
	
}	
?>